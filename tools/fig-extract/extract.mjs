import { parseFig, nodeId } from 'openfig-core';
import { readFileSync, writeFileSync, mkdirSync, existsSync } from 'fs';
import path from 'path';

const FIG_PATH = 'C:\\Users\\HP Probook\\Downloads\\Flor de Marula.fig';
const OUT_DIR = 'C:\\Users\\HP Probook\\source\\repos\\flor-de-marula\\design';

if (!existsSync(OUT_DIR)) mkdirSync(OUT_DIR, { recursive: true });

const data = new Uint8Array(readFileSync(FIG_PATH));
console.log('Parsing .fig file...');
const doc = parseFig(data);

console.log('Header:', doc.header);
console.log('Total nodes:', doc.nodes.length);
console.log('Total images:', doc.images.size);

// --- Build parent->children id map already provided as doc.childrenMap ---
// Find root(s): nodes with no parentIndex or parent not in nodeMap
const allIds = new Set(doc.nodes.map(n => nodeId(n)).filter(Boolean));
const roots = doc.nodes.filter(n => {
  const pid = n.parentIndex && n.parentIndex.guid ? `${n.parentIndex.guid.sessionID}:${n.parentIndex.guid.localID}` : null;
  return !pid || !allIds.has(pid);
});
console.log('Root-ish nodes:', roots.length);

function summarize(node, depth, maxDepth, lines) {
  if (depth > maxDepth) return;
  const id = nodeId(node);
  const size = node.size ? `${Math.round(node.size.x)}x${Math.round(node.size.y)}` : '';
  const tx = node.transform ? `@(${Math.round(node.transform.m02)},${Math.round(node.transform.m12)})` : '';
  lines.push('  '.repeat(depth) + `[${node.type}] "${node.name}" id=${id} ${size} ${tx}`);
  const children = doc.childrenMap.get(id) || [];
  for (const c of children) summarize(c, depth + 1, maxDepth, lines);
}

// Print full tree down to depth 4 starting from roots (DOCUMENT -> CANVAS/PAGE -> FRAME top-level)
const treeLines = [];
for (const r of roots) summarize(r, 0, 3, treeLines);
writeFileSync(path.join(OUT_DIR, 'tree-overview.txt'), treeLines.join('\n'), 'utf8');
console.log('Wrote tree-overview.txt (' + treeLines.length + ' lines)');

// List all top-level FRAME nodes under any CANVAS/PAGE (these are typically the named screens)
const frameLines = [];
function findFrames(node, depth) {
  if (node.type === 'FRAME' || node.type === 'COMPONENT' || node.type === 'SYMBOL') {
    const id = nodeId(node);
    const size = node.size ? `${Math.round(node.size.x)}x${Math.round(node.size.y)}` : '?';
    frameLines.push(`depth=${depth} id=${id} type=${node.type} size=${size} name="${node.name}"`);
  }
  const id = nodeId(node);
  const children = doc.childrenMap.get(id) || [];
  for (const c of children) findFrames(c, depth + 1);
}
for (const r of roots) findFrames(r, 0);
writeFileSync(path.join(OUT_DIR, 'frames-list.txt'), frameLines.join('\n'), 'utf8');
console.log('Wrote frames-list.txt (' + frameLines.length + ' entries)');

// Export full node dump as JSON (careful with size / circular refs)
function safeNode(n) {
  const { parentIndex, ...rest } = n;
  return {
    ...rest,
    id: nodeId(n),
    parentId: parentIndex && parentIndex.guid ? `${parentIndex.guid.sessionID}:${parentIndex.guid.localID}` : null,
  };
}
const allNodesSafe = doc.nodes.map(safeNode);
writeFileSync(path.join(OUT_DIR, 'all-nodes.json'), JSON.stringify(allNodesSafe, null, 1));
console.log('Wrote all-nodes.json');

// Export images with detected extensions
const imgDir = path.join(OUT_DIR, 'images');
if (!existsSync(imgDir)) mkdirSync(imgDir, { recursive: true });

function detectExt(bytes) {
  if (bytes[0] === 0x89 && bytes[1] === 0x50 && bytes[2] === 0x4e && bytes[3] === 0x47) return 'png';
  if (bytes[0] === 0xff && bytes[1] === 0xd8) return 'jpg';
  if (bytes[0] === 0x47 && bytes[1] === 0x49 && bytes[2] === 0x46) return 'gif';
  if (bytes[0] === 0x52 && bytes[1] === 0x49 && bytes[2] === 0x46 && bytes[3] === 0x46) return 'webp';
  if (bytes[0] === 0x3c && bytes[1] === 0x73 || (bytes[0]===0x3c && bytes[1]===0x3f)) return 'svg';
  return 'bin';
}

let count = 0;
const imageManifest = [];
for (const [name, bytes] of doc.images.entries()) {
  const ext = detectExt(bytes);
  const outName = name + '.' + ext;
  writeFileSync(path.join(imgDir, outName), Buffer.from(bytes));
  imageManifest.push({ hash: name, file: outName, bytes: bytes.length, ext });
  count++;
}
writeFileSync(path.join(OUT_DIR, 'images-manifest.json'), JSON.stringify(imageManifest, null, 1));
console.log('Exported', count, 'images to', imgDir);

// thumbnail
if (doc.thumbnail) {
  writeFileSync(path.join(OUT_DIR, 'thumbnail.png'), Buffer.from(doc.thumbnail));
  console.log('Wrote thumbnail.png');
}

console.log('DONE');

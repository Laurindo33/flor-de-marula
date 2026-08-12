import { parseFig, nodeId } from 'openfig-core';
import { readFileSync, writeFileSync, existsSync, mkdirSync } from 'fs';
import path from 'path';

const FIG_PATH = 'C:\\Users\\HP Probook\\Downloads\\Flor de Marula.fig';
const OUT_DIR = 'C:\\Users\\HP Probook\\source\\repos\\flor-de-marula\\design';

const data = new Uint8Array(readFileSync(FIG_PATH));
const doc = parseFig(data);

// map hash-object -> hex string, and hex -> exported filename
const manifest = JSON.parse(readFileSync(path.join(OUT_DIR, 'images-manifest.json'), 'utf8'));
const hashToFile = new Map(manifest.map(m => [m.hash, m.file]));

function hashHex(imgHash) {
  if (!imgHash) return null;
  const bytes = [];
  for (let i = 0; i < 20; i++) {
    if (imgHash[i] === undefined) break;
    bytes.push(imgHash[i]);
  }
  return bytes.map(b => b.toString(16).padStart(2, '0')).join('');
}

function colorHex(c) {
  if (!c) return null;
  const r = Math.round(c.r * 255), g = Math.round(c.g * 255), b = Math.round(c.b * 255);
  const hex = '#' + [r, g, b].map(v => v.toString(16).padStart(2, '0')).join('');
  return c.a < 1 ? `${hex} (a=${c.a.toFixed(2)})` : hex;
}

function simplifyFills(fillPaints) {
  if (!fillPaints) return undefined;
  return fillPaints.filter(f => f.visible !== false).map(f => {
    if (f.type === 'SOLID') return { type: 'SOLID', color: colorHex(f.color), opacity: f.opacity };
    if (f.type === 'IMAGE') {
      const hex = hashHex(f.image && f.image.hash);
      return { type: 'IMAGE', file: hashToFile.get(hex) || ('MISSING:' + hex), scaleMode: f.scaleMode };
    }
    if (f.type && f.type.startsWith('GRADIENT')) {
      return { type: f.type, stops: (f.stops || []).map(s => ({ pos: s.position, color: colorHex(s.color) })) };
    }
    return { type: f.type };
  });
}

function simplifyEffects(effects) {
  if (!effects) return undefined;
  return effects.filter(e => e.visible !== false).map(e => ({
    type: e.type,
    color: colorHex(e.color),
    radius: e.radius,
    offset: e.offset,
    spread: e.spread,
  }));
}

function buildNode(node, parentAbs) {
  const id = nodeId(node);
  const tx = node.transform || { m02: 0, m12: 0 };
  const abs = { x: (parentAbs?.x || 0) + tx.m02, y: (parentAbs?.y || 0) + tx.m12 };

  const out = {
    id,
    type: node.type,
    name: node.name,
  };
  if (node.size) out.size = { w: Math.round(node.size.x), h: Math.round(node.size.y) };
  out.pos = { x: Math.round(abs.x), y: Math.round(abs.y) };
  if (node.cornerRadius !== undefined) out.cornerRadius = node.cornerRadius;
  if (node.rectangleCornerRadiiIndependent) {
    out.cornerRadii = [node.rectangleTopLeftCornerRadius, node.rectangleTopRightCornerRadius, node.rectangleBottomRightCornerRadius, node.rectangleBottomLeftCornerRadius];
  }
  const fills = simplifyFills(node.fillPaints);
  if (fills && fills.length) out.fills = fills;
  const strokes = simplifyFills(node.strokePaints);
  if (strokes && strokes.length) { out.strokes = strokes; out.strokeWeight = node.strokeWeight; }
  const fx = simplifyEffects(node.effects);
  if (fx && fx.length) out.effects = fx;
  if (node.opacity !== undefined && node.opacity !== 1) out.opacity = node.opacity;
  if (node.stackMode && node.stackMode !== 'NONE') {
    out.layout = {
      mode: node.stackMode,
      spacing: node.stackSpacing,
      padding: [node.stackPaddingTop, node.stackPaddingRight, node.stackPaddingBottom, node.stackPaddingLeft],
      primaryAlign: node.stackPrimaryAlignItems,
      counterAlign: node.stackCounterAlignItems,
      primarySizing: node.stackPrimarySizing,
      counterSizing: node.stackCounterSizing,
    };
  }

  if (node.type === 'TEXT') {
    out.text = node.textData ? node.textData.characters : '';
    out.font = node.fontName ? `${node.fontName.family} ${node.fontName.style}` : undefined;
    out.fontSize = node.fontSize;
    out.lineHeight = node.lineHeight;
    out.letterSpacing = node.letterSpacing;
    out.textAlign = node.textAlignHorizontal;
    // per-character style overrides (multi-style text)
    if (node.styleOverrideTable && node.styleOverrideTable.length) {
      out.styleOverrides = node.styleOverrideTable.map(s => ({
        fontSize: s.fontSize, font: s.fontName ? `${s.fontName.family} ${s.fontName.style}` : undefined,
        fills: simplifyFills(s.fillPaints),
      }));
    }
  }

  const children = doc.childrenMap.get(id) || [];
  if (children.length) {
    out.children = children.map(c => buildNode(c, abs));
  }
  return out;
}

function findById(targetId) {
  return doc.nodes.find(n => nodeId(n) === targetId);
}

const targets = process.argv.slice(2);
for (const t of targets) {
  const [frameId, label] = t.split('=');
  const node = findById(frameId);
  if (!node) { console.error('Not found:', frameId); continue; }
  const tree = buildNode(node, { x: 0, y: 0 });
  const outFile = path.join(OUT_DIR, `detail-${label || frameId.replace(':', '_')}.json`);
  writeFileSync(outFile, JSON.stringify(tree, null, 1));
  console.log('Wrote', outFile);
}

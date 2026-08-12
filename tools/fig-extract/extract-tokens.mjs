import { parseFig, nodeId } from 'openfig-core';
import { readFileSync, writeFileSync } from 'fs';
import path from 'path';

const FIG_PATH = 'C:\\Users\\HP Probook\\Downloads\\Flor de Marula.fig';
const OUT_DIR = 'C:\\Users\\HP Probook\\source\\repos\\flor-de-marula\\design';

const data = new Uint8Array(readFileSync(FIG_PATH));
const doc = parseFig(data);

function colorHex(c) {
  const r = Math.round(c.r * 255), g = Math.round(c.g * 255), b = Math.round(c.b * 255);
  return '#' + [r, g, b].map(v => v.toString(16).padStart(2, '0')).join('').toUpperCase();
}

const colorUsage = new Map(); // hex -> {count, opacity set, names[]}
const typoUsage = new Map(); // key(family|style|size|lineHeight) -> {count, names[]}

for (const node of doc.nodes) {
  if (node.phase === 'REMOVED') continue;
  const fills = node.fillPaints || [];
  for (const f of fills) {
    if (f.visible === false) continue;
    if (f.type === 'SOLID' && f.color) {
      const hex = colorHex(f.color);
      const entry = colorUsage.get(hex) || { count: 0, names: new Set(), nodeTypes: new Set(), opacities: new Set() };
      entry.count++;
      entry.names.add(node.name);
      entry.nodeTypes.add(node.type);
      entry.opacities.add((f.opacity ?? 1).toFixed(2));
      colorUsage.set(hex, entry);
    }
  }
  const strokes = node.strokePaints || [];
  for (const s of strokes) {
    if (s.type === 'SOLID' && s.color && s.visible !== false) {
      const hex = colorHex(s.color) + ' (stroke)';
      const entry = colorUsage.get(hex) || { count: 0, names: new Set(), nodeTypes: new Set(), opacities: new Set() };
      entry.count++;
      entry.names.add(node.name);
      colorUsage.set(hex, entry);
    }
  }
  if (node.type === 'TEXT' && node.fontName) {
    const lh = node.lineHeight ? `${node.lineHeight.value}${node.lineHeight.units === 'PERCENT' ? '%' : node.lineHeight.units === 'PIXELS' ? 'px' : ''}` : 'auto';
    const key = `${node.fontName.family} | ${node.fontName.style} | ${node.fontSize}px | lh:${lh} | ls:${node.letterSpacing ?? 0}`;
    const entry = typoUsage.get(key) || { count: 0, samples: new Set() };
    entry.count++;
    if (node.textData) entry.samples.add((node.textData.characters || '').slice(0, 40));
    typoUsage.set(key, entry);
  }
}

const colorLines = [...colorUsage.entries()]
  .sort((a, b) => b[1].count - a[1].count)
  .map(([hex, e]) => `${hex}  x${e.count}  opacities=[${[...e.opacities].join(',')}]  types=[${[...e.nodeTypes].join(',')}]  e.g. names: ${[...e.names].slice(0, 6).join(' | ')}`);
writeFileSync(path.join(OUT_DIR, 'tokens-colors.txt'), colorLines.join('\n'));

const typoLines = [...typoUsage.entries()]
  .sort((a, b) => b[1].count - a[1].count)
  .map(([key, e]) => `${key}  x${e.count}\n   samples: ${[...e.samples].slice(0, 3).join(' || ')}`);
writeFileSync(path.join(OUT_DIR, 'tokens-typography.txt'), typoLines.join('\n\n'));

console.log('Colors found:', colorUsage.size);
console.log('Typography combos found:', typoUsage.size);
console.log('Wrote tokens-colors.txt and tokens-typography.txt');

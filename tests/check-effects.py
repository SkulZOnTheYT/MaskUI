"""Verify the documented mask table exactly matches the code, in tier order."""
from pathlib import Path
import re
root = Path(__file__).resolve().parents[1]
source = (root / 'src/main/java/com/github/skulzontheyt/maskui/MaskType.java').read_text()
wiki = (root / 'wiki.md').read_text()
blocks = re.findall(r'    (\w+)\("(\w+)",.*?java.util.Arrays.asList\((.*?)\)\)\)', source, re.S)
rows = [line.split('|') for line in wiki.splitlines() if re.match(r'\| [1-7] \|', line)]
assert len(blocks) == len(rows) == 7
roman = {1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V'}
for (enum, mask_id, block), row in zip(blocks, rows):
    assert row[2].strip() == ('Wither Skeleton' if mask_id == 'wither' else mask_id.title())
    effects = re.findall(r'new MaskEffect\("(\w+)", (\d+)', block)
    assert ('NIGHT_VISION', '0') in effects
    expected = [name.replace('_', ' ').title() + ' ' + roman[int(amplifier) + 1] for name, amplifier in effects if name != 'NIGHT_VISION']
    assert row[5].strip().split(', ') == expected, (mask_id, row[5], expected)
assert "<version>1.0.0</version>" in (root / 'pom.xml').read_text()
assert "version: '1.0.0'" in (root / 'src/main/resources/plugin.yml').read_text()
print('DOC_TEST_PASS: all seven effect tables match code; version remains 1.0.0')

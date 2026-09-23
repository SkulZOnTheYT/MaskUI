"""Build separate Paper smoke plugins using the workspace's local server libraries."""
from pathlib import Path
import shutil
import subprocess
root = Path(__file__).resolve().parents[2]
jar = root / 'dev/target/MaskUI-1.0.0.jar'
server = root / 'dev/target/smoke-server'
classpath = ':'.join(str(p) for p in (root / 'server/libraries').rglob('*.jar'))
classpath += ':' + str(jar) + ':' + str(root / 'server/plugins/Vault.jar')
for name in ['RightClickSmoke', 'TradeSmoke']:
    output = root / 'dev/target' / (name + '-classes')
    output.mkdir(exist_ok=True)
    subprocess.run(['javac', '--release', '21', '-cp', classpath, '-d', str(output), str(root / 'dev/tests' / (name + '.java'))], check=True)
    (output / 'plugin.yml').write_text(f"name: {name}\nversion: '1'\nmain: {name}\napi-version: '1.21'\ndepend: [MaskUI, Vault]\n")
    subprocess.run(['jar', '--create', '--file', str(server / 'plugins' / (name + '.jar')), '-C', str(output), '.'], check=True)
# Reuse the previous test-server filename to avoid loading two copies of MaskUI.
shutil.copy2(jar, server / 'plugins/MaskUI-2.1.0.jar')

import json
from pathlib import Path
p = Path('geojson/tanzania.geojson')
with p.open(encoding='utf-8') as f:
    data = json.load(f)
for feat in data['features']:
    props = feat['properties']
    name = props.get('NAME_1') or props.get('name') or 'Unknown'
    coords = feat['geometry']['coordinates'][0]
    lats = [pt[1] for pt in coords]
    lngs = [pt[0] for pt in coords]
    print(f"{name}: lat({min(lats):.3f},{max(lats):.3f}) lng({min(lngs):.3f},{max(lngs):.3f})")

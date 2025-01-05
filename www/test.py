import subprocess

# Lista dei file rinominati
renamed_files = [
    ("502A0194.JPG", "502A0194.jpg"),
    ("502A0194_25percent.JPG", "502A0194_25percent.jpg"),
    ("502A0194_50percent.JPG", "502A0194_50percent.jpg"),
    ("502A0194_5percent.JPG", "502A0194_5percent.jpg"),
    ("502A0194_75percent.JPG", "502A0194_75percent.jpg"),
    ("502A1075.JPG", "502A1075.jpg"),
    ("502A1075_25percent.JPG", "502A1075_25percent.jpg"),
    ("502A1075_50percent.JPG", "502A1075_50percent.jpg"),
    ("502A1075_5percent.JPG", "502A1075_5percent.jpg"),
    ("502A1075_75percent.JPG", "502A1075_75percent.jpg"),
    ("502A1331.JPG", "502A1331.jpg"),
    ("502A1331_25percent.JPG", "502A1331_25percent.jpg"),
    ("502A1331_50percent.JPG", "502A1331_50percent.jpg"),
    ("502A1331_5percent.JPG", "502A1331_5percent.jpg"),
    ("502A1331_75percent.JPG", "502A1331_75percent.jpg"),
    ("502A1372.JPG", "502A1372.jpg"),
    ("502A1372_25percent.JPG", "502A1372_25percent.jpg"),
    ("502A1372_50percent.JPG", "502A1372_50percent.jpg"),
    ("502A1372_5percent.JPG", "502A1372_5percent.jpg"),
    ("502A1372_75percent.JPG", "502A1372_75percent.jpg"),
    ("502A1434_BW.JPG", "502A1434_BW.jpg"),
    ("502A1434_BW_25percent.JPG", "502A1434_BW_25percent.jpg"),
    ("502A1434_BW_50percent.JPG", "502A1434_BW_50percent.jpg"),
    ("502A1434_BW_5percent.JPG", "502A1434_BW_5percent.jpg"),
    ("502A1434_BW_75percent.JPG", "502A1434_BW_75percent.jpg"),
    ("IMG_7357.JPG", "IMG_7357.jpg"),
    ("IMG_7357_25percent.JPG", "IMG_7357_25percent.jpg"),
    ("IMG_7357_50percent.JPG", "IMG_7357_50percent.jpg"),
    ("IMG_7357_5percent.JPG", "IMG_7357_5percent.jpg"),
    ("IMG_7357_75percent.JPG", "IMG_7357_75percent.jpg"),
    # Aggiungi qui altre coppie di file rinominati
]

# Esegui il comando `git mv` per ogni coppia di file
for old_name, new_name in renamed_files:
    try:
        print(f"Eseguendo: git mv '{old_name}' '{new_name}'")
        subprocess.run(["git", "mv", old_name, new_name], check=True)
    except subprocess.CalledProcessError as e:
        print(f"Errore durante il rinominare {old_name} -> {new_name}: {e}")

# Aggiungi i file rinominati e crea un commit
try:
    print("Aggiungendo le modifiche con git add...")
    subprocess.run(["git", "add", "."], check=True)
    print("Creando un commit...")
    subprocess.run(["git", "commit", "-m", "Rinominati file con estensioni in minuscolo"], check=True)
    print("Facendo il push al repository remoto...")
    subprocess.run(["git", "push"], check=True)
except subprocess.CalledProcessError as e:
    print(f"Errore durante l'aggiunta, commit o push: {e}")

print("Operazione completata.")

import os

def esporta_codice_progetto(output_file="progetto_laravel.txt"):
    # Cartelle da ignorare assolutamente
    ignore_dirs = {
        'node_modules', 'vendor', '.git', 'storage', 
        'bootstrap/cache', 'public/storage', 'tests'
    }
    
    # Estensioni di file che ci interessano
    allowed_extensions = {'.php', '.js', '.vue', '.css', '.scss', '.env.example', '.json'}
    
    # File specifici da ignorare (opzionale)
    ignore_files = {'package-lock.json', 'composer.lock'}

    with open(output_file, "w", encoding="utf-8") as f_out:
        for root, dirs, files in os.walk("."):
            # Filtra le cartelle da ignorare
            dirs[:] = [d for d in dirs if d not in ignore_dirs]
            
            for file in files:
                ext = os.path.splitext(file)[1]
                if ext in allowed_extensions and file not in ignore_files:
                    file_path = os.path.join(root, file)
                    
                    f_out.write(f"\n{'='*50}\n")
                    f_out.write(f"FILE: {file_path}\n")
                    f_out.write(f"{'='*50}\n\n")
                    
                    try:
                        with open(file_path, "r", encoding="utf-8") as f_in:
                            f_out.write(f_in.read())
                    except Exception as e:
                        f_out.write(f"[ERRORE NELLA LETTURA DEL FILE: {e}]\n")
                    
                    f_out.write("\n\n")

    print(f"Esportazione completata! Trovi tutto in: {output_file}")

if __name__ == "__main__":
    esporta_codice_progetto()
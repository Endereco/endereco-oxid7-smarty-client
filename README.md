# endereco-oxid7-client
Das offizielle Plugin von endereco zur Adressvalidierung und Kundenstamdatenprüfung für OXID 7 Webshops, die ein Smarty basiertes Theme verwenden (flow,wave)

## Installation

Die Installation erfolgt in folgenden Schritten:

1. Das Modul über Composer installieren

`composer require endereco/endereco-oxid7-client-smarty`

Der Befehl lädt die neuste Version herunter. Um eine spezielle Version zu installieren, zum Beispiel *4.6.7*, kann
der Befehl folgenderweise angepasst werden.

`composer require endereco/endereco-oxid7-client-smarty:4.6.7`

2. Migrationen ausführen (optional)

Standardweise führt das Endereco-Oxid6-Modul notwendige Datenbankänderungen nach der Aktivierung automatisch aus. Die 
Anpassungen liegen alle in der [Installer Datei](./src/Installer/Installer.php) in der *onActivate* Methode.

Falls man jedoch mindestens die Version 6.2.3 des Shops hat und die Änderungen der Datenbank über Migrationen 
ausführen möchte, kann man die default-Anpassungen der Datenbank deaktivieren.

Dafür muss in der *config.inc.php* Datei des Shops folgender Code hinzugefügt werden.

`$this->bEnderecoUseMigrations = true;`

Ab jetzt wird der ganze Code-Block in *onActivate* nicht mehr ausgeführt. Nach der Neuinstallation des Endereco-Oxid6-Moduls, 
sowie nach jedem Update des Moduls, sollen Migrationen geprüft und eventuell eingespielt werden:

`vendor/bin/oe-eshop-db_migrate migrations:migrate`

[Siehe Dokumentation für Migrationen in Oxid 7](https://docs.oxid-esales.com/developer/en/7.2/development/tell_me_about/migrations.html)


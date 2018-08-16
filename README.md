# ygg rss feed downloader

## Installation
Cloner le repo dans un répertoire, assurez vous que les bonnes permissions et les bons groupes soit appliqués.
Renomez config.default.php en config.php avec votre éditeur préféré et modifiez les lignes pour renseigner votre couple login/pass. Vous pouvez également passez sync à ```true``` si vous souhaitez que le script fetch l'URL de YGG directement sur github, sinon, le script utilisera l'url présente dans le fichier domain

```
return array(
    'user' => 'user',
    'pass' => 'pass',
    'sync' => false
);
```

## Utilisation
Mettez les liens officiels YGG du RSS dans rutorrent, par exemple https://yggtorrent.is/rss?type=2&parent_category=2145&sub_category=2184 pour les séries.

Il faut créer une règles de remplacement des URL dans le flux rss. Choisir ```Si l'URL du torrent à charger correspond au modèle``` et mettre
```
/https:\/\/.{3}.yggtorrent\.is\/engine\/torrent_generator\/torrent_file\?torrent=(\d+)\/pass/i
```
Puis choisir ```alors replacer l'URL du torrent à charger par``` et mettre le lien ceci en remplaçant par votre IP/domaine
```
https://monserveur.com/yggrss/dl.php?action=download&idt=${1}
```

#### Paye moi une bière !
Si ce que j'ai fait te plait et que ça t'es utile, paye moi une bière ;)
- Paypal : https://paypal.me/guiisch
- ETH: 0xbE08c367280EF1b945c327419Afec474B5E1eff6

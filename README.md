# Giftbox
- Muhammet BAYRAM
- Loïc DURAND
- Johan SCHAEFFER
- Léo THOMAS
S4DWM1

### Ressources
Dépot Git : https://github.com/Bayram-Muhammet-Malik/giftbox
URL Docketu : http://docketu.iutnc.univ-lorraine.fr:16795/

### Installation
- Copiez coller le fichier ``.database_env.dist`` en ``.database_env`` et remplir le fichier
- Fait de même pour ``gift.appli/src/conf/gift.db.conf.ini.dist`` en ``gift.appli/src/conf/gift.db.conf.ini``
- Une fois les fichiers complété avec la bonne configuration
- Lancer docker (s'il n'est pas déjà lancé) puis ouvrez un terminal dans le repertoire racine du projet giftbox et excecutez la commande suivante : ``docker compose up``

### Fonctionnalité réalisées
- Muhammet :
    - 2 (Afficher le détail d'une prestation)
    - 4 (liste de catégories)
    - 7 (Création d'une Box vide)
    - 9 (ajout de prestations dans la box)
- Loïc :
    - 1 (Afficher la liste des prestations)
    - 2 (Afficher le détail d'une prestation)
    - 4 (liste de catégories)
    - 14 (signin)
    - 11 (validation d'une box)
- Johan :
    - 1 (Afficher la liste des prestations)
    - 3 (liste de prestations par catégories)
    - 4 (liste de catégories)
    - 5 (affichage des coffrets types classés par thèmes)
    - 6 (affichage d’un coffret type)
    - 21 (api : liste des catégories)
    - 23 (api : accès à une box)
- Léo :
    - 10 (affichage d'une box)
    - 12 (génération de l'URL d'accès)
    - 13 (utilisation de l’url : accès au contenu de la box)
    - 15 (register)
    - 20 (api : liste des prestations)
    - 22 (api : liste des prestations d'une catégorie)

### Données pour tester
Compte permissions de base :
- Mail ``aurore06@example.org``, mdp ``aurore06``

Compte admin (pas utile dans ce projet) :
- Mail ``admin@gift.net``, mdp ``admin``
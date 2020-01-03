# joel-beya-blog

# Installation

``` bash
$ git clone git@github.com:joelbeya/joel-beya-blog.git
```

``` bash
$ cd joel-beya-blog/
```

``` bash
$ composer install
```

``` bash
$ composer update
```

``` bash
$ cd scripts/
```

``` bash
$ php bin/console doctrine:database:create
```

``` bash
$ php bin/console doctrine:schema:update --force
```

``` bash
$ php bin/console make:migration
```

``` bash
$ php bin/console doctrine:migrations:migrate
```

``` bash
$ php bin/console doctrine:schema:update --force
```

``` bash
$ cd .. && symfony serve
```

# Usage

## Cloner le git et aller sur le repertoire /joel-beya-blog: 
### Aller sur le dossier /joel-beya-blog/scripts:
Lancer le script **`database.sh`**
Ou tout simplement les commandes **`php bin/console doctrine:database:create && php bin/console doctrine:schema:update --force`**
Enfin faire les migrations pour re-créer l'entity Article
### En suite revenir sur le dossier racine /joel-beya-blog:
Exécuter la commande **`symfony serve`**

## Les scripts (/script)

+ database.sh : Crée la base de données (create schema + create sqlite.db)
+ production.sh : Pour pouvoir créer la base de donnée sur heroku (create schema + create postgresql.db)


# Présentation du blog

## Utilisateur

Il y a 2 types d'utilisateurs
+ Connecté
+ Non connecté

### Non connecté
L'utilisateur non connecté verra :

+ La barre de tâches avec les trois boutons avec un **LogIn/Register** lui permettant de se s'enregistrer et de créer un compte si ça n'est pas dèjà le cas ou de se connecter si il a déjà un compte
+ En effet, outre que le bouton **Add** qui n'apparaît qu'après qu'il soit connecté, la plupart des actions qu'on peut retrouver sur la page `show` du bouton **Read More  &rarr;** l'enverront systématique à la page de connection

### Connecté
L'utilisateur connecté,

+ Après connection et redirection vers la page d'acceuil, on voit systématiquement apparaître le bouton **Add** sur la barre de tâche qui donne la possibilité de créer/ajouter un article.
+ On peut désormais sur la page `show` du bouton **Read More  &rarr;** modifier un article (bouton **Edit**) ou quasiment le supprimer (Bouton **Remove**)

### Profile
L'utilisateur est connecté,

+ Sur la barre de tâche apparaît un bouton avec le nom de l'utilisateur menant à la page de configuration du profil utilsateur

+ Possibilité de modifier les informations du profile
+ Possibilité de se deconnecter

## Articles

### Voir les articles

Page par défault (Accueil)

+ Sur la barre de tâche, le logo en bleu **Beya N. Joel's blog** ramène toujours à l'acceuil

+ 3 articles par page (Trie par date de publication décroissante) avec une pagination pour pouvoir plus d'articles

### Rechercher un article (Par son titre)

Sur la page d'accueil

+ Rentrer le titre de l'article rechercher, dans le champ juste en dessous de "Rechercher un article", en haut de la liste des articles.
+ Cliquer sur "Lancer la recherche"

La nouvelle liste des articles correspondant à la recherche apparait.

La recherche n'est pas sensible aux majuscules/minuscules.

### Poster un article
Seul les utilisateurs connectés peuvent poster un article,

Sur la barre de tâche, appuyer sur le boutton **`Add`**

### Details d'un article

Sur la liste des articles (Page d'accueil)
+ Cliquer sur le bouton **`Read More  &rarr;`**

Cette page permet de lire l'article en entier. Et vous pouvez aimer l'article

#### Supprimer un article

Seul un utilisateur connecté peut le supprimer.

Sur la page détails de l'article.
+ Cliquer sur le bouton **`Remove`**

Si l'utilisateur n'est pas l'auteur, un message d'erreur apparait et on est redirigé vers la page d'acceuil

Si l'utilisateur n'est pas connecté, il est redirigé sur la page de connexion.

#### Editer un article

Seul un utilisateur connecté et auteur de l'article peut l'éditer.

Sur la page détails de l'article.
+ Cliquer de "Editer"

Vous pouvez choisire un nouveau titre et/ou un nouveau texte.

Cliquer sur "Sauvegarder"

Si l'utilisateur n'est pas l'auteur, un message d'erreur apparait.

Si l'utilisateur n'est pas connecté, il est redirigé sur la page de connexion.

### A propos


Une page de présentation, lien vers le répertoire github

# Fonctionnement du blog (Backend)

## Controlleurs

+ BlogController : Renvoie sur les différentes pages du blog
+ CrudController : Gère les différentes actions liées aux "Artciles" (Add/Show/Edit/Remove), 

## Gestion des utilisateurs
FOSUserBundle
+ Override du dossier templates/FOSUserBundle/Security/Profile : Pour la gestion du compte utilisateur d'un utilisateur (Edit/Show)

+ Override du dossier templates/FOSUserBundle/Security/Registration : Pour l'enregistrement et la création du compte Utilisateur

+ Override du dossier templates/FOSUserBundle/Security/Security : Pour la connexion et l'obtention des privilèges utilisateurs.

https://github.com/FriendsOfSymfony/FOSUserBundle

## CSS

Bootstrap & W3Css

### Exemple utilisé

https://www.w3schools.com/w3css/tryw3css_templates_blog.htm


## Les formulaires
Les formulaires de création et de mofication sont gérés par Symfony.

## Base de données 

**`Ubuntu`** : Sqlite 3.2
**`Heroku`** : PostgreSQL 9.5

## Validation W3C

https://validator.w3.org

+ Accueil : https://validator.w3.org/nu/?doc=http%3A%2F%2Fjoel-beya-blog.herokuapp.com%2Fpage
+ About : https://validator.w3.org/nu/?doc=http%3A%2F%2Fjoel-beya-blog.herokuapp.com%2Fabout
+ CV : http://joel-beya-blog.herokuapp.com/cv

Pour la gestion des utilisateurs 

+ Profile : http://joel-beya-blog.herokuapp.com/profile/
+ Modification profile : http://joel-beya-blog.herokuapp.com/profile/edit
+ Enregistrement : http://joel-beya-blog.herokuapp.com/login
+ Connexion : http://joel-beya-blog.herokuapp.com/register/

Pour la gestion des articles

+ Ajout/ Création : http://joel-beya-blog.herokuapp.com/add
+ Modification : http://joel-beya-blog.herokuapp.com/edit/2
+ Lecture : http://joel-beya-blog.herokuapp.com/show/2


# Ce qui n'a pas été fait
+ Un groupe d'utilisateur ADMIN, pouvant editer ou supprimer TOUS les postes. Dans notre cas, seul l'auteur peut supprimer son poste.

+ Une gestion avancée des images, avec dépôt des images sur un site de stockage par exemple.



# Guide

## Projet 
Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1


## Badge

<a href="https://codeclimate.com/github/MickaelMoley/projet8-TodoList/maintainability"><img src="https://api.codeclimate.com/v1/badges/793c89bba1d9c427e255/maintainability" /></a>

## Installation/cloner le projet
Lancer cette commande depuis un terminal afin d'installer les sources du projet en local :


    git clone git@github.com:MickaelMoley/projet8-TodoList.git

## Installer les dépendances du projet
Lancer la commande suivante pour installer les dépendances du projet :

    composer install

## Mise en place de la base de donnée
Vous devez renseigner les identifiants de la base de donnée dans le fichier suivant : `.env`
ex : `DATABASE_URL="mysql://root:password@127.0.0.1:3306/nom_de_la_db"`
Enfin,
Exécuter les commandes suivantes pour synchroniser votre base de donnée avec les entités du projet


### Création de la base de donéne
    php bin/console doctrine:database:create (si cela n'est pas déjà fait)
###     Forcer la synchronisation de la base de donnée
    php bin/console doctrine:schema:update --force

## Jeu de données
Le projet contient des données de test.
Pour démarrer le projet avec un jeu de donnée de test, lancer simplement cette commande :

    bin/console doctrine:fixtures:load

## Tester le projet avec PHPUnit
Pour tester le projet, il suffit de lancer la commande suivante :

    
    vendor/bin/phpunit

## Jeu de données
Le projet contient des données de test.
Pour démarrer le projet avec un jeu de donnée de test, lancer simplement cette commande :

    bin/console doctrine:fixtures:load


## Tester le projet sur un serveur

Pour commencer, lancer le serveur via la commande **symfony**.
(si l'application est installé sur votre machine)

    symfony serve
puis accéder à l'URL donné depuis la console. Généralement, c'est cette URL : http://localhost:8000.

ou

en exécutant la commande suivante :

    cd public && php -S localhost:8000

Enfin, accéder à l'URL suivante : https://localhost:8000/
Vous pourrez accéder à l'application.

## Voir le taux de couverture de code de l'application
Pour voir le taux de couverture de code, il suffit accéder à la route suivante :

    http://{SERVER_NAME}/test-coverage/index.html

où `{SERVER_NAME}` est le nom du domaine de l'application.

Ex : http://localhost:8000/test-coverage/index.html

**Si vous souhaitez générer à nouveau le rapport de taux de couverture de code, il suffit de lancer la commande  qui suit                       :**

    vendor/bin/phpunit --coverage-html public/test-coverage
    
Puis accéder à nouveau à l'URL au-dessus pour voir le nouveau rapport généré.

## Contribuer au projet
Pour connaître comment contribuer au projet, vous trouverez dans les sources un fichier qui se nomme **CONTRIBUTING.MD** . Il contient les instructions et les pratiques permettant de contribuer au projet.

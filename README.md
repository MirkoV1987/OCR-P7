# OCR-P7 - BileMo<br/>
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/d19c9088cfce4bef915def020dbc7f9f)](https://www.codacy.com/manual/MirkoV1987/OCR-P7?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MirkoV1987/OCR-P7&amp;utm_campaign=Badge_Grade)</br>
Projet 7 de mon parcours de Développeur d'applications - PHP/SYMFONY chez OpenClassrooms. Création d'un web service exposant une API. Utilisation de Symfony 4, des bundles JMS Serializer et Hateoas pour créer l'application demandée par l'entreprise BileMo.

<b>Environnement utilisé durant le développement</b>
<li>Symfony 4.4</li>
<li>LexikJWTAuthenticationBundle</li>
<li>JMSSerializerBundle</li>
<li>BazingaHateoasBundle</li>
<li>NelmioApiDocBundle</li>
<li>Composer 1.8.0</li>
<li>WampServer 3.1.9</li>
<li>Apache 2.4.39</li>
<li>PHP 7.2.18</li>
<li>PhpMyAdmin 4.8.5</li> 
<li>MySQL 5.7.26</li>
<br/>
<b>INSTALLATION</b>
</br>
<li>Clonez ou téléchargez le repository GitHub dans le dossier voulu :</li></br>
</br>

    git clone https://github.com/MirkoV1987/OCR-P7.git
</br>
<li>Chargez toutes les dépendances du projet avec Composer, en lançant la commande :</li>
</br>

    composer update
</br>
<b>INSTALLATION DE LA BASE DE DONNÉES</b>
<li>Configurez vos variables d'environnement tel que la connexion à la base de données dans le fichier .env.</li>
</br>

    DATABASE_URL=mysql://DB_USER:DB_PASSWORD@127.0.0.1:3306/DB_NAME?serverVersion=SERVER_VERSION
</br>
<li>Créez la base de données avec la commande Doctrine :</li>
</br>

    bin/console doctrine:database:create
</br>
<li>La base de données a été créée. Générez les tables du database en lançant la commande :</li>
</br>

    bin/console doctrine:schema:update --force
</br>
<b>LANCEMENT DES FIXTURES (FACULTATIF)</b>
<li>Installer les fixtures pour avoir une démo de données fictives :</li></br>
</br>

    bin/console doctrine:fixtures:load
</br>
<b>CRÉATION D'UN TOKEN AVEC JWT</b>
<li>Insérez JWT_PASSPHRASE in bilemo/.env :</li></br>
</br>

    JWT_PASSPHRASE=YourPassPhrase
</br>
<li>Générez les clés SSH de JWTAuthentication (<a href="https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation" target="_blank">Documentation officielle</a>) :</li></br>
</br>

    $ mkdir -p config/packages/jwt
    $ openssl genrsa -out config/packages/jwt/private.pem 4096
    $ openssl rsa -pubout -in config/packages/jwt/private.pem -out config/packages/jwt/public.pem
</br>
<b>USAGE AVEC POSTMAN</b>
<li>Installez et paramétrez Postman (<a href="https://www.postman.com/" target="_blank">Site officiel</a>)</li></br>
<li>URL - votre nom de domaine et le lien d'accès suivant</li></br>
</br>

    Lien d'accès : api/login_check
</br>
<li>Vous pouvez tester l'API avec ce compte client, déjà disponible :</li></br>
</br>

    {
        "username" : "client1@gmail.com",
        "password" : "Client1"
    }
</br>
<b>DOCUMENTATION</b>
<li>La documentation au format JSON est disponible au lien suivant :</li></br>
</br>

    Lien d'accès : api/doc.json
</br>
<li>La documentation au format .doc est disponible également au lien suivant :</li></br>
</br>

    Lien : <a href="https://docs.google.com/document/d/e/2PACX-1vSOs8dqK1kCZbIdIXoOmpMFlEFMuyLe4UhXIhm0weshPon2HFZ-l1t6aHXNpxe_eUK8L5civno3213I/pub" target="_blank"></a>
</br>
<li>Félicitations ! Vous pouvez vous servir de ce projet à votre guise !</li>

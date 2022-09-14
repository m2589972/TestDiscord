# TestDiscord

Cette extension TestDiscord ajoute un robot discord dans wordpress.

Le code de l'extension est proposé pour faire des tests, il est à destination de développeurs qui voudront créer leur propre robot.

Pour utiliser cette extension, il faut déjà avoir un compte discord et ensuite l'extension détaille toute la configuration à faire. Elle présente des exemples de ce qu'il est possible de faire avec un hébergement classique en utilisant l'HTTP, donc sans avoir besoin d'être connecté continuellement en tant que WebSocket.

Parmis les exemples : 
- commandes tapée dans un salon, réponses à une commande avec textes et boutons. Pour cette partie, le site doit être accessible par internet pour que discord puisse communiquer avec le site. Actuellement, l'hébergeur o2switch bloque cet accès entres ses sites et discord donc cette partie ne fonctionnera pas chez cet hébergeur..
- lire les messages d'un salon et écrire des messages.

Je rajoute un conseil pour les développeurs : organisez votre code en faisant un effort pour bien séparer chaque partie. Discord est une entreprise donc son but premier est de gagner de l'argent et non de faciliter la vie de ses clients. Je vous conseille donc de ne pas être dépendant de discord et d'être prêt à modifier votre code si vous trouvez un autre système à utiliser à la place de discord.
Comme alternative pour les discussions en vidéo, il y a par exemple le logiciel libre Jitsi Meet : https://jitsi.org/jitsi-meet/. Une instance de Jitsi Meet est utilisable là par exemple : https://meet.roflcopter.fr/

[Téléchargez la dernière version de TestDiscord en cliquant ici (version 4 du 14/09/2022)](https://github.com/m2589972/TestDiscord/releases/download/v4/TestDiscord.4.zip)


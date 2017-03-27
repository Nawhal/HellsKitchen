//quels plats sont les plus commandés pour un restaurant (la requete compte le nb de fois ou les plats on etet commandes dans le restau?)
//il faudra trier le tableau et prendre du coup la premiere ligne


select plat.nomPlat,SUM(quantiteElement.quantite) FROM commande,quantiteelement,element,plat WHERE commande.idRestaurant=1 AND commande.idCommande=quantiteelement.idCommande AND quantiteElement.idElement=element.idElement AND element.idElement=plat.idElement GROUP BY nomPlat;

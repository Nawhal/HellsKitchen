//Test pour trouver le prix des dessert commandes

//il reste a faire en php la moyenne du coup et on obtiendra la somme que depense en moyenne les clients pout les dessert. (C'est un peu different de ce qui est demande car c'est par par commande mais pour le restau)

select distinct plat.nomplat, prixElement.prixElement, quantiteElement.quantite FROM commande,quantiteElement,element,plat,carte,prixElement, periodeCarte WHERE commande.idRestaurant=1 AND commande.idCommande=quantiteElement.idCommande AND quantiteElement.idElement=element.idElement AND element.idElement=plat.idElement AND plat.dessert=true AND element.idElement=prixElement.idElement ;

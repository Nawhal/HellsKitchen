//Test pour trouver le prix des dessert commandes
//fonctionne bizzarement
select plat.nomplat, prixElement.prixElement, quantiteElement FROM commande,quantiteElement,element,plat,carte,prixElement, periodeCarte WHERE commande.idRestaurant=1 AND commande.idCommande=quantiteElement.idCommande AND quantiteElement.idElement=element.idElement AND element.idElement=plat.idElement AND plat.dessert=true AND element.idElement=prixElement.idElement ;

select plat.nomplat, prixElement.prixElement FROM commande,quantiteElement,element,plat,carte,prixElement, periodeCarte WHERE commande.idRestaurant=1 AND commande.idCommande=quantiteElement.idCommande AND quantiteElement.idElement=element.idElement AND element.idElement=plat.idElement AND plat.dessert=true AND element.idElement=prixElement.idElement ;

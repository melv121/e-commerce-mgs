Ce dossier contient les images des variantes de couleur des produits.
Chaque variante de couleur d'un produit doit être nommée selon le format:
product{ID}_{color}.jpg

Exemple:
- product1_black.jpg
- product1_red.jpg
- product1_blue.jpg

Cela permet d'associer facilement les images aux variantes de couleur dans la base de données.

Il inclut:
- teeshirtnikehomme.jpg, teeshirtnikehomme1.jpg, etc.: Variantes du T-shirt Nike Homme
- teeshirtteddyhomme.jpg, teeshirtteddyhomme2.jpg, etc.: Variantes du T-shirt Teddy Smith Homme
- teeshirtadidashomme1.jpg, teeshirtadidashomme2.jpg, etc.: Variantes du T-shirt Adidas Homme
- teeshirtnikefemme.jpg, teeshirtnikefemme2.jpg, etc.: Variantes du T-shirt Nike Femme
- teeshirtadidasfemme.jpg, teeshirtadidasfemme2.jpg, etc.: Variantes du T-shirt Adidas Femme
- teeshirtteddyenfant.jpg, teeshirtteddyenfant2.jpg, etc.: Variantes du T-shirt Teddy Smith Enfant
- teeshirtnikenfant.jpg, teeshirtnikenfant2.jpg, etc.: Variantes du T-shirt Nike Enfant

Pour ajouter de nouvelles variantes:
1. Placez les images dans ce dossier
2. Ajoutez les données dans la table product_variants via SQL

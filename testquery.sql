
INSERT INTO `recipe` (`recipeID`, `recipeName`, `author`, `instructions`, `notes`) 
    VALUES ('1', 'Congee', NULL, 
        'Add rice to pot of water, bring to a boil then turn heat down to a simmer.\r\n\r\nAdd chicken bouillon, stir to incorporate.\r\n\r\nAdd optional ingredients, simmer until desired texture. ', 
        'WHEN COOKING: Adjust water to preference. Congee will continue to thicken as it cools.\r\n\r\nFOR TASTE: It is recommended to add grated or sliced ginger, sliced spring onion, and soysauce. Other common toppings include fried breadsticks, century egg, and fermented vegetables.');

INSERT INTO `type`(`recipeID`, `type`)
    VALUES ('1', 'Side');

INSERT INTO `user` (`userID`, `username`) 
    VALUES ('1', 'Angie');

INSERT INTO `raw` (`rawID`, `rawName`) 
    VALUES ('1', 'Water'), 
    ('2', 'Chicken Stock'), 
    ('3', 'Chicken Bouillon'), 
    ('4', 'Rice');

INSERT INTO `substitute` (`rawID`, `subOf`) 
    VALUES ('2', '1');

INSERT INTO `ingredient` (`recipeID`, `ingredientID`, `measurement`,`unit`,`preparation`) 
    VALUES ('1', '1', '6','Cup', NULL),
    ('1', '2', '1','Cup', 'Washed'),
    ('1', '3', '6','Teaspoon', NULL);

INSERT INTO `ingredientRaw` (`recID`, `ingID`, `rawID`) 
    VALUES ('1', '1', '1'),
    ('1', '2', '4'),
    ('1', '3', '3');

INSERT INTO `inPantry` (`userID`, `rawID`) 
    VALUES ('1', '1'),
    ('1', '3'),
    ('1', '4');


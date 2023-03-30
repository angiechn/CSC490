ALTER TABLE whatsdinner.reviewed
MODIFY review int;

DELETE FROM whatsdinner.user;

ALTER TABLE `user`
ADD `email` VARCHAR(255) NOT NULL;

ALTER TABLE `user`
ADD `password` VARCHAR(45) NOT NULL;

INSERT INTO whatsdinner.user (`userID`, `username`, `email`, `password`) 
    VALUES ('1', 'Test', 'Test@gmail.com', 'Test'),
    ('2', 'Angie', 'a_chen2@uncg.edu', 'Chen'),
    ('3', 'Jamie', 'jchernan@uncg.edu', 'Farmer'),
    ('4', 'Faith', 'fkpippen@uncg.edu', 'Pippenger');
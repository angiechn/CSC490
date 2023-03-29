ALTER TABLE whatsdinner.reviewed
MODIFY review int;

INSERT INTO whatsdinner.user (`userID`, `username`) 
    VALUES ('1', 'Test'),
    ('2', 'Angie'),
    ('3', 'Jamie'),
    ('4', 'Faith');
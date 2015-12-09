ALTER TABLE houses_templates ADD instead int(3) NOT NULL DEFAULT '0'
COMMENT '0-базовая постройка, 1..-наследственная постройка';


UPDATE houses_templates SET instead = 1 WHERE id=2;
UPDATE houses_templates SET instead = 2 WHERE id=3;
UPDATE houses_templates SET instead = 3 WHERE id=4;
UPDATE houses_templates SET instead = 6 WHERE id=7;
UPDATE houses_templates SET instead = 7 WHERE id=8;
UPDATE houses_templates SET instead = 9 WHERE id=10;
UPDATE houses_templates SET instead = 10 WHERE id=11;
UPDATE houses_templates SET instead = 11 WHERE id=12;
UPDATE houses_templates SET instead = 13 WHERE id=14;
UPDATE houses_templates SET instead = 14 WHERE id=15;
UPDATE houses_templates SET instead = 15 WHERE id=16;

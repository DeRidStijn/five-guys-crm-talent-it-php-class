-- // 001: Changing contact ownership

UPDATE `contact` SET `member_id` = 1 WHERE `contact_id` <> 1;

-- //@UNDO

UPDATE `contact` SET `member_id` = `contact_id` WHERE `contact_id` <> 1;

-- //Done
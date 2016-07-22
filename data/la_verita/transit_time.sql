create TABLE transit_time(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    from_branch_id INT(11) NOT NULL,
    to_branch_id INT(11) NOT NULL,
    hours INT(11) NOT NULL,
    FOREIGN KEY (from_branch_id) REFERENCES branch(id),
	FOREIGN KEY (to_branch_id) REFERENCES branch(id)
)
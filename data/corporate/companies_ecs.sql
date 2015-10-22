DROP TABLE IF EXISTS company_branches;

CREATE TABLE IF NOT EXISTS company_branches (
    id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    company_id INT(11) NOT NULL,
    branch_id INT(11) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    KEY k_cb_company_id (`company_id`),
    KEY k_cb_branch_id (`branch_id`),
    CONSTRAINT fk_company_branches_company_id FOREIGN KEY (`company_id`) REFERENCES company(`id`),
    CONSTRAINT fk_company_branches_branch_id FOREIGN KEY (`branch_id`) REFERENCES branch(`id`)
);
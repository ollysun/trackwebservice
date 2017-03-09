INSERT INTO shipping_type (id, name) VALUE (5, 'intl expressd documents');
INSERT INTO shipping_type (id, name) VALUE (6, 'intl express non-documents');
INSERT INTO shipping_type (id, name) VALUE (7, 'intl economy express');

ALTER TABLE `intl_tariff`
DROP FOREIGN KEY `FK_intl_tariff_parcel_type`;
ALTER TABLE `intl_tariff`
ADD CONSTRAINT `FK_intl_tariff_parcel_type` FOREIGN KEY (`parcel_type_id`) REFERENCES `shipping_type` (`id`);
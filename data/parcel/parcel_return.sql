CREATE TABLE `return_reasons` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `status_code` VARCHAR(11) NOT NULL,
  `meaning_of_status` VARCHAR(100) NOT NULL,
  `usage_of_status` VARCHAR (150) NOT NULL
);

 INSERT INTO  `tnt`.`return_reasons` (
`id` ,
`status_code` ,
`meaning_of_status` ,
`usage_of_status`
)
VALUES (
NULL ,  '00',  'Delivered',  'When a shipment is delivered
'
), (
NULL ,  '01',  'Unable to locate the address - please advise telephone no.
',  'when the address cannot be located by the courier and could be a correct address but you need a tel no to get better description
'
),(
NULL ,  '02',  'Illegible address - please advise full details  ',  'When the address is not readable/illegible/incorrect
'
), (
NULL ,  '04',  'Company not known at the address ',  'When the company is not located at the address given
'
),(
NULL ,  '05',  'Shipment refused (For cancelled Order)State remarks ',  'When the Customer refuses the Shipment for any reason…
'
), (
NULL ,  '06',  'Contact person not known at the address',  'When the address is correct and located but the contact person is not known at the address
'
), (
NULL ,  '08',  'Not in / closed on delivery - will re-attempt tomorrow  ',  'When the cnee is not at home at delivery attempt
'
), (
NULL ,  '12',  'Future Delivery Requested ',  'When the receiver requests for future delivery
'
), (
NULL ,  '13',  'Consignee moved - new address located ',  'Receiver has moved from the address but new address has been received/located
'
),(
NULL ,  '14',  'Consignee moved - please provide new info.Consignee moved - please provide new info.',  'When the receiver has moved and we are not aware of the new address for delivery
'
), (
NULL ,  '15',  'Shipment held for pick-up',  'When the Receiver ask for the shipment to be held in the office for self pickup
'
), (
NULL ,  '17',  'Shipment received',  'Shipment has been received in your station
'
), (
NULL ,  '19',  'Bags arrived at destination',  'Bags have arrived physically in your station
'
),(
NULL ,  '21',  'Onforwarded to Agent station delivery in outer location',  'When the shipment is meant for an Onforwarding location and has been forwarded for delivery
'
), (
NULL ,  '23',  'Out for delivery ',  'Shipment is Out for Delivery
'
), (
NULL ,  '49',  'SHIPMENT ON HOLD - AT STATION',  'Shipment is held in the station and not forwarded for delivery due to any reason
'
), (
NULL ,  '51',  'Rerouted to correct station directly',  'A Misrouted shipment that has been sent to the correct station
'
), (
NULL ,  '52',  'Manifested but not arrived(Missing at Import station)',  'Shipment  deals detstated on the stations Manifest but Physical shipment not received in the station
'
), (
NULL ,  '54',  'Package arrived unmanifested',  'Shipment arrived in the station but came without being maniifested
'
), (
NULL ,  '55',  'Shipment improperly packed - delay while re-packing  ',  'Shipment not packed properly and repackaging is on going
'
), (
NULL ,  '56',  'Shipment damaged on arrival - attempting delivery',  'Damaged shipment arrived the station and has been OK by the receiver to deliver in that condition
'
), (
NULL ,  '58',  'Multiple package shipment - not all pieces arrived ',  'When a Mutiple piece shipment is received in your station but some pieces are not available…hence not all pieces was received
'
), (
NULL ,  '59',  'MISROUTE',  'Received a Misrouted shipment
'
), (
NULL ,  '70',  'Shipment returned to sender as agreed with export station',  'A Shipment that has been tried and refused /cancelled by the receiver and customer service has been contacted and an OK was given to return to Lagos
'
);


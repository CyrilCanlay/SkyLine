SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `PAYDAY` 
(
  `Weapon` varchar(11) ,
  `rof` int(11) ,
  `mun` int(11) ,
  `mag` int(11) ,
  `dmg` int(11) ,
  `acc` int(11) ,
  `stb` int(11) ,
  `con` int(11) ,
  `thr` int(11) ,
  `rep` int(11) ,
  `Source` int(11) 
)
;

DELETE FROM `HOTEL` WHERE IdH IS NOT NULL

INSERT INTO `HOTEL` (`IdH`, `Prix`, `Distance`, `NbEt`) VALUES
('H1', 30, 800, 3),
('H2', 35, 800, 3),
('H3', 60, 400, 3),
('H4', 60, 100, 5),
('H5', 50, 300, 4),
('H6', 60, 300, 4)
;


CREATE OR REPLACE VIEW HOTEL_SKY 
AS
SELECT 	IdH, Prix, Distance, NbEt
FROM 	HOTEL h1
WHERE 	NOT EXISTS 
(
	SELECT	*
	FROM	HOTEL h2
	WHERE	h2.Prix <= h1.Prix
	AND		h2.Distance <= h1.Distance
	AND		h2.NbEt >= h1.NbEt
	AND 
	(
			h2.Prix < h1.Prix
		OR 	h2.Distance < h1.Distance
		OR 	h2.NbEt > h1.NbEt
	)
)
;

CREATE OR REPLACE VIEW MIN_MAX
AS
SELECT Min(Prix) Min_Prix, Min(Distance) Min_Distance, Max(NbEt) Max_NbEt
FROM HOTEL_SKY
;

CREATE OR REPLACE VIEW HOTEL_NORM
AS
SELECT 	HS.IdH
		,
		(MM.Min_Prix/HS.Prix) Prix_Norm
		,
		(MM.Min_Distance/HS.Distance) Distance_Norm
		,
		(HS.NbEt/MM.Max_NbEt) NbEt_Norm
FROM HOTEL_SKY HS, MIN_MAX MM
;

CREATE OR REPLACE VIEW HOTEL_POND
AS
SELECT 	IdH
		,
		(0.5*Prix_Norm) Prix_Pond
		,
		(0.25*Distance_Norm) Distance_Pond
		,
		(0.25*NbEt_Norm) NbEt_Pond
FROM HOTEL_NORM
;

CREATE OR REPLACE VIEW HOTEL_SCORE
AS
SELECT 	IdH
		,
		Prix_Pond
		,
		Distance_Pond
		,
		NbEt_Pond
		,
		(Prix_Pond+Distance_Pond+NbEt_Pond) Score
FROM HOTEL_POND
;

SELECT H.IdH, H.Prix, H.Distance, H.NbEt, S.Score
FROM HOTEL H, HOTEL_SCORE S
WHERE H.IdH = S.IdH
ORDER BY S.Score Desc
;

SELECT *
FROM HOTEL
;
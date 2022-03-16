use gsb_frais;
UPDATE FicheFrais
SET idEtat = "CL"
WHERE idEtat = "CR"
    AND MONTH(mois) < MONTH(CURRENT_DATE());
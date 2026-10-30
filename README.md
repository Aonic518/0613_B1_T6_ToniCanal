# Votacions de l'institut

## Preguntes de reflexió

1. Guardem qui ha votat al servidor perquè l'usuari no pugui modificar aquesta dada. Si fos una cookie, podria esborrar-la o canviar-la des del navegador i tornar a votar.

2. Si un usuari canvia la cookie de darrera visita, podria mostrar un temps incorrecte. Ho controlem validant que sigui un timestamp enter, positiu i que no sigui una data futura.

3. El timeout de 5 minuts controla la inactivitat de la sessió i les dades es guarden al servidor. La cookie de darrera visita dura 30 dies i es guarda al navegador de l'usuari.

4. Perquè el navegador no envia la cookie nova fins a la petició següent. A la petició actual, `$_COOKIE` encara conté el valor anterior.

## Posada en marxa

- Executa el projecte amb un servidor PHP (per exemple, XAMPP).
- Assegura't que la carpeta `dades` té permís d'escriptura.
- No pugis `dades/vots.json` al repositori.
# 0613_B1_T6_ToniCanal

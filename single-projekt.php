<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */



get_header(); ?>

    <!-- Her har vi vores HTML-struktur, som vi plotter vores data ind i ved single-view  -->

	<section id="primary" class="content-area"></section>
		<main id="main" class="site-main">
		<article class="projekt">
        <h1></h1>
		 <img class="pic" src="" alt="" />
        <p class="trin"></p>
		<p class="korttekst"></p>
        </article>
	    </main>
    
    
    <!-- Her starter vi vores JS til at få vist projekterne i single-view  -->  
    <script>
        let projekt; //her laver vi en variabel for vores projekter i singularis, altså projekt, og dette fordi vi nu skal koncentrere os om hver enkelt, og ikke i flertal
        
        //her laver vi en konstant til vores url, som vi bruger til at fetche vores data ind, og den php snippet der står til slut, bruger vi for at få fat i ID'et for hvert projekt
        const url = "http://perfpics.dk/kea/2_sem/09_cms/unesco_wp/wp-json/wp/v2/projekt/"+<?php echo get_the_ID()?>;
        
        //her kører vi funktionen som henter vores data ind, og til slut kalder vi funktionen der skal vise det i vores DOM
        async function hentData() {
            const respons = await fetch(url);
            projekt = await respons.json(); 
            console.log(projekt); 
            visData()
        }
        //her kører vi funktionen som skal vise det, og her er det ikke en klon længere, men en individuel indsættelse så vi skriver document i stedet for klon
        function visData() {
            document.querySelector("h1").textContent = projekt.title.rendered; //vi har skrevet 'rendered' til sidst, fordi vi i console kunne se at titlen hed det
            document.querySelector(".pic").src = projekt.foto.guid; //vi har skrevet 'guid' til sidst, fordi vi i console kunne se at billedet hed det
            document.querySelector(".trin").textContent = projekt.trin; 
            document.querySelector(".korttekst").textContent = projekt.korttekst; 
        }

        hentData(); 
    </script>  


<?php get_footer(); ?>

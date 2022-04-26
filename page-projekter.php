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

//til at begynde med har vi kopieret parent-temaets page.php, og så har vi navngivet den 'page-projekter', og slettet alt php indhold i main, så vi kan skrive vores eget//


get_header(); ?>
<!-- Nedenfor her har vi lavet en skabelon (template), som vores data fra pods' skal klones ind i  -->

 <template>
    <article class="projekt">
      	 <img class="pic" src="" alt="projekter" /> 
        <h3></h3>
        <p class="trin"></p>
		<p class="korttekst"></p>
    </article>
 </template>

<section id="primary" class="content-area"></section><!-- #primary -->
<main id="main" class="site-main">
 <!-- Herunder opretter vi et nav-element som vi kan bruge til vores filtrering af projekterne   --> 
<nav id="filtrering"><button class="alle" data-projekt="alle">Alle</button></nav>
<!-- Her opretter vi en 'tom' section, som vi senere bruger til at klone vores indhold (data) ind i  -->
<section class="data-container"></section>	

</main><!--slut main -->

<!-- Herunder starter vi JS koden, som skal hjælpe os med at hente dataerne ind  -->

<script>
  /*   Herunder har vi oprettet variable, som vi skal bruge igennem koden  */
    let projekter; 
    let categories; 
    let proId; // test 
    let filterProjekt = "alle"; //default værdi vi har givet så alle vises inden klik på specifik
    let trin; 

    /* Herunder har vi lavet en konstant med navnet 'url', som repræsenterer vores link som i næste led bruges til at fetche dataerne ind */
    const url = "http://perfpics.dk/kea/2_sem/09_cms/unesco_wp/wp-json/wp/v2/projekt?per_page=100"; 

    /* Herunder har vi lavet en konstant med navnet 'url', som repræsenterer vores link som i næste led bruges til at fetche dataerne ind */
    const catUrl = "http://perfpics.dk/kea/2_sem/09_cms/unesco_wp/wp-json/wp/v2/categories?per_page=100"; 
    
    /* Herunder kører vi den funktion som henter vores data ind (både vores custom field data, og vores kategorier), for at tjekke at indhentingen fungerer, console logger vi og tjekker der, før vi fortsætter */
    async function hentData() {
        const respons = await fetch(url);
        const catRespons = await fetch(catUrl); 
       /*  const idRespons = await fetch(idUrl); BRUGT TIL AT TESTE NOGET  */

        projekter = await respons.json(); 
        categories = await catRespons.json(); 
       /*  proId = await idRespons.json(); BRUGT TIL AT TESTE NOGET */
        console.log(projekter); 
      /*   Herunder kalder vi den funktion som skal hjælpe os med at vise dataen på siden  */
        visData(); 
        
        /* Herunder kalder vi en ny funktion, nemlig den funktion vi bruger til at få oprettet vores filtreringsknapper */
        opretKnapper();     
    }
    
   /*  I nedstående funktion sørger vi med 'forEach', og ved brug af ID for at der oprettes en knap for hver kategori der er lavet */
    function opretKnapper(){
        categories.forEach(cat => {
            document.querySelector("#filtrering").innerHTML += `<button class="filter cat${cat.id}" data-projekt="${cat.id}">${cat.name}</button>`
        })
        kaldKnapper(); //her kalder vi knapperne, og med det menes der at vi 'aktiverer' knapperne, ved at tilføje eventlistener der gør dem klikbare
    }

    function kaldKnapper() {
        document.querySelectorAll("#filtrering button").forEach(elm =>{
            elm.addEventListener("click", filtrering) //efter der er klikket på en kategori, kalder vi funktionen filtrering
        })
    }

/*   //test herunder
    function styleProjekter() {
      if(trin == "Indskoling") {
        console.log("indskoling"); 
      }
      else if(trin == "Mellemtrin"){
        console.log("mellemtrin"); 
      }
      else{
        console.log("virk nu!!!"); 
      } SHIT SHIT SHIT LORTET VIRKER IKKEEEEEE
    }   */

/* 
let trin = projekt.trin; 
      if(trin == "indskoling"){
        console.log("hej"); 
      }  */
  
    function filtrering(){
     filterProjekt = this.dataset.projekt; //her gør vi det klart at der skal filtreres på det der er klikket på ved brug af 'this'
     console.log(filterProjekt); 
     visData(); //vi kalder vores vis funktion, så det rigtige indhold til den rigtige kategori kan vises
    }
   /*  I nedstående funktion sørger vi for at få klonet, og vist vores data */
    function visData() {
        /* Som det første i denne funktion, sørger vi for at få oprettet to konstanter, vores data container, og vores template */
        const container = document.querySelector(".data-container");
        const temp = document.querySelector("template");
        container.textContent = ""; //denne sætning sørger for at vores container er tom før hver vising, og det er vigtigt så den forrige kategori man har trykket på, ikke vises ved næste klik
        
        /* I det nedestående stykke kode der kloner vi vores data-felter ind i den article vi har gjort klar, og vi bruger de ord som vi har brugt da vi oprettede podsene */
        projekter.forEach(projekt => {
            if (filterProjekt == "alle" || projekt.categories.includes(parseInt(filterProjekt))){
            let klon = temp.cloneNode(true).content;
            klon.querySelector(".projekt").classList.add(projekt.trin); 

            klon.querySelector(".pic").src = projekt.foto.guid; //vi har skrevet 'guid' til sidst, fordi vi i console kunne se at billedet hed det
            klon.querySelector("h3").textContent = projekt.title.rendered; //vi har skrevet 'rendered' til sidst, fordi vi i console kunne se at titlen hed det
            klon.querySelector(".trin").textContent = projekt.trin; 
            klon.querySelector(".korttekst").textContent = projekt.korttekst; 
            /* Herunder gør vi artiklerne klikbare, og sender videre til det enkelte projekts side, som vi har skabt inde i single-projekt */
            klon.querySelector(".projekt").addEventListener("click", ()=> {location.href = projekt.link;})

          /*   Til slut herunder tilføjer vi alle vores kloninger til vores container  */
            container.appendChild(klon);}

        })
    }

    hentData(); //vores vigtige kald der sørger for at hente dataerne ind
</script>


<!-- Her starter styling til loop-view af projekter  -->

<style>
.data-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  grid-gap: 8px;
}

.projekt.Ungdomsuddannelse{
  background-color: #FCCB8E; 
}

.projekt.Ungdomsudannelse /* - denne her er magen til ovenstående, men fordi der var en stavefejl findes den i to */ {
  background-color: #FCCB8E; 
}

.projekt.Indskoling {
  background-color: #FFBB9B; 
}

.projekt.Mellemtrin {
  background-color: #FDC30A; 
}

.projekt.Udskoling{
  background-color: #F0B0CA; 
}

/* kategori-knappernes styling herunder:  */

#filtrering /* - generelle indstillinger til knapperne */ {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap; 
 justify-content: center;   
  gap: 8px; 
  padding-bottom: 10px; 
}

.filter /* - generelle indstillinger til ikonernes størrelse / style */{
  border: 0; 
  height: 150px; 
  width: 150px; 
}

.projekt { 
  margin:  8px;
  padding: 8px; 
}

.trin {
  text-decoration-line: underline; /* denne er lavet for at give klassetrinnet en streg under  */
}

.alle /* - generelle indstillinger til ALLE knappen / ikonet */{
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/alle.webp); 
  background-size: cover; 
  border: 0; 
  height: 150px; 
  width: 150px; 
  color: rgba(255, 255, 255, 0); 
}

/* Hover indstillinger - gjort for at fjerne / skjule de oprindelige kategoritekster, så det kun er ikonet */
.alle:hover {
  color: rgba(255, 255, 255, 0); 
}

.filter:hover {
  color: rgba(255, 255, 255, 0); 
}

/* Herunder er der indsat ikon for hver knap, samt fjerning af oprindelig tekst  */
.filter.cat4 /* - afskaf fattigdom 1  */{
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal-ikon-01.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat6 /* - Stop sult 2  */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal02.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat8 /* - Sundhed og trivsel 3  */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal03.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat11 /* - kvalitetsuddannelse 4  */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal04.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat13 /* - ligestilling mellem kønnene 5  */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal05.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat15 /* - Rent vand og sanitet 6  */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal06.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat17  /* - bæredygtig energi 7 */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal07.webp); 
  background-size: cover;  
  color: rgba(255, 255, 255, 0); 
}

.filter.cat19  /* - anstændige jobs og økonomisk vækst 8  */ {
 background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal08.webp); 
 background-size: cover; 
 color: rgba(255, 255, 255, 0); 
}

.filter.cat21  /* - industri innovation og infrastruktur 9  */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal09.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat23 /* - Mindre ulighed 10 */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal10.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat25 /* - bæredygtige byer og lokalsamfund 11 */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal11.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat27  /* - ansvarligt forbrug og produktion 12  */ {
 background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal12.webp); 
 background-size: cover; 
 color: rgba(255, 255, 255, 0); 
}

.filter.cat29  /* - klimaindsats 13 */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal13.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat31 /* - Livet i havet 14 */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal14.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat34 /* - Livet på land 15 */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal15.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}

.filter.cat36 /* - fred, retæfridghed og stærke institutioner 16 */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal16.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}


.filter.cat38 /* - Partnerskaber for handling 17 */ {
  background-image: url(http://perfpics.dk/kea/2_sem/09_cms/vm_ikoner_webp/Verdensmaal17.webp); 
  background-size: cover; 
  color: rgba(255, 255, 255, 0); 
}


</style>


<?php get_footer(); ?>

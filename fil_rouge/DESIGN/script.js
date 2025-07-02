// Effet "machine à écrire"
    const texte = "Bonjour! Bienvenue sur Plateform_QCM";
    const cible = document.getElementById("texte-anim");
    let index = 0;

    function ecrireTexte() {
      if (index < texte.length) {
        cible.innerHTML += texte.charAt(index);
        index++;
        setTimeout(ecrireTexte, 60); // vitesse d’écriture
      }
    }

    ecrireTexte();

    // Animation d’apparition lente pour les cartes
    const faders = document.querySelectorAll(".fade-in");

    const options = {
      threshold: 0.1
    };

    const observer = new IntersectionObserver(function(entries, observer) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("appear");
          observer.unobserve(entry.target); // une seule fois
        }
      });
    }, options);

    faders.forEach(el => {
      observer.observe(el);
    });

    document.addEventListener("DOMContentLoaded", function() {
      const fadeEls = document.querySelectorAll('.fade-in');
      fadeEls.forEach((el, i) => {
        setTimeout(() => {
          el.classList.add('visible');
        }, 150 * i); // effet décalé pour chaque boîte
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const backArrow = document.getElementById('back-arrow');
      if (backArrow) {
          backArrow.addEventListener('click', function() {
              window.history.back();
          });
      }
  });
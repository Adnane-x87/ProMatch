---
marp: true
theme: default
_class: lead
paginate: true
backgroundColor: #f8fafc
style: |
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
  
  section {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px;
    color: #0f172a;
    padding: 50px 70px;
    background: #f8fafc;
  }

  h1 { 
    color: #3d8a54;
    font-weight: 800;
    font-size: 2.8em;
    margin-bottom: 10px;
  }

  h2 { 
    color: #0f172a; 
    font-size: 1.8em; 
    border-bottom: 3px solid #4da565;
    padding-bottom: 10px;
    margin-bottom: 40px;
    font-weight: 700;
  }

  h3 { color: #64748b; font-weight: 600; margin-top: 0; }

  /* Cover Slide Customization */
  section.lead {
    background: linear-gradient(135deg, #f0f9f1 0%, #ffffff 100%);
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
  }
  
  section.lead h1 {
    background: linear-gradient(90deg, #3d8a54, #4da565);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
  }

  .logo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 85%;
    position: absolute;
    top: 40px;
  }
  .logo-header img { height: 120px; }

  /* Summary Grid */
  .sommaire-grid, .sommaire-grid-3 {
    display: grid;
    gap: 20px;
    margin-top: 20px;
  }
  .sommaire-grid {
    grid-template-columns: 1fr 1fr;
  }
  .sommaire-grid-3 {
    grid-template-columns: 1fr 1fr 1fr;
    gap: 15px;
  }
  .sommaire-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-left: 6px solid #4da565;
    padding: 12px 20px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease;
  }
  .sommaire-grid-3 .sommaire-item {
    padding: 10px 12px;
    border-radius: 12px;
  }
  .sommaire-num {
    background: #4da565;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 800;
    margin-right: 20px;
    flex-shrink: 0;
  }
  .sommaire-grid-3 .sommaire-num {
    width: 30px;
    height: 30px;
    margin-right: 10px;
    font-size: 0.85em;
  }
  .sommaire-text { font-weight: 600; color: #1e293b; }
  .sommaire-grid-3 .sommaire-text { font-size: 0.85em; }

  .img-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 70%;
  }
  .img-container-large {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 92%;
    margin-top: -5px;
  }
  .img-methodo {
    max-width: 90%;
    max-height: 400px;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  }
  .img-uml {
    width: 98%;
    height: auto;
    max-height: 580px;
    object-fit: contain;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  }

  /* Custom Section Padding for UML slides */
  section.uml-slide {
    padding: 20px 40px 10px 40px;
  }
  section.uml-slide h2 {
    font-size: 1.45em;
    padding-bottom: 5px;
    margin-bottom: 8px;
  }
  section.uml-slide h3 {
    font-size: 0.95em;
    margin-bottom: 5px;
  }

  /* Fullscreen UML slide - maximum image space */
  section.uml-full {
    padding: 8px 15px 5px 15px;
  }
  section.uml-full h2 {
    font-size: 1.2em;
    padding-bottom: 3px;
    margin-bottom: 3px;
  }
  section.uml-full h3 {
    font-size: 0.85em;
    margin-bottom: 3px;
  }
  .img-uml-full {
    width: 100%;
    height: auto;
    max-height: 660px;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  /* Empty Image Placeholder for Architecture slide */
  .img-placeholder {
    width: 98%;
    height: 520px;
    border: 3px dashed #cbd5e1;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 0.9em;
    font-weight: 600;
  }

  /* Conclusion slide */
  .conclusion-single-card {
    background: white;
    border-radius: 16px;
    padding: 25px 35px;
    border-left: 8px solid #4da565;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    margin-top: 25px;
  }
  .conclusion-single-card h3 {
    color: #3d8a54;
    font-size: 1.1em;
    margin-bottom: 12px;
    font-weight: 700;
  }
  .conclusion-single-card p {
    font-size: 0.82em;
    color: #334155;
    line-height: 1.6;
    margin-bottom: 12px;
  }
  .conclusion-single-card p:last-child {
    margin-bottom: 0;
  }

  .dt-card {
    background: white;
    padding: 25px;
    border-radius: 16px;
    border-top: 6px solid #4da565;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }

---

<!-- _class: lead -->
<div class="logo-header">
  <img src="/Presentation/images/ofppt-logo.png" alt="OFPPT">
  <img src="/rapport/images/logo-solicode.png" alt="Solicode">
</div>

# Projet de Fin de Formation
### Système Intelligent de Gestion de Terrains <h3 style="color: #4da565;">- ProMatch</h3>

**Réalisé par :** Adnane Kesksu  
**Encadré par :** M. ESSARRAJ Fouad  
**Filière :** Développement Mobile et Web  

**Date :** 12 juin 2026  

---
## 📋 Sommaire

<div class="sommaire-grid-3">
  <div class="sommaire-item"><div class="sommaire-num">1</div><div class="sommaire-text">Contexte du projet</div></div>
  <div class="sommaire-item"><div class="sommaire-num">2</div><div class="sommaire-text">Méthodologie</div></div>
  <div class="sommaire-item"><div class="sommaire-num">3</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">4</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">5</div><div class="sommaire-text">Conception</div></div>
  <div class="sommaire-item"><div class="sommaire-num">6</div><div class="sommaire-text">Réalisation</div></div>
  <div class="sommaire-item"><div class="sommaire-num">7</div><div class="sommaire-text">Démonstration Live</div></div>
  <div class="sommaire-item"><div class="sommaire-num">8</div><div class="sommaire-text">Conclusion</div></div>
</div>


---

## 1. Contexte du projet

<div class="img-container">
  <img src="./images/context.png" class="img-methodo" alt="Contexte">
</div>


---

## 2. Méthodologie : Design Thinking

<div class="img-container">
  <img src="images/designThinking.png" class="img-methodo" alt="Design Thinking">
</div>


---

## Méthodologie : Scrum (Agile)

<div class="img-container">
  <img src="images/scrum.jpg" class="img-methodo" alt="Scrum">
</div>




---

## 3. Branche Fonctionnelle

### Cadrage de problème

<div class="img-container">
  <div class="dt-card" style="border-top-color: #ef4444; width: 85%;">
    <h3 style="color: #ef4444; margin-bottom: 15px;">Problématique</h3>
    <blockquote style="font-style: italic; background: #f8fafc; padding: 20px; border-radius: 12px; border-left: 5px solid #ef4444; font-size: 0.9em; line-height: 1.6; margin: 0;">
      "Comment digitaliser la gestion des terrains pour éliminer les pertes de réservations, les conflits de créneaux et la dépendance au téléphone ?"
    </blockquote>
    <p style="margin-top: 20px; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 0;">
      🎯 <strong>Focus :</strong> L'automatisation, la vérification d'identité (CNI) et le contrôle en temps réel.
    </p>
  </div>
</div>

---
<!-- _class: uml-slide -->

## 3. Branche Fonctionnelle — Global Use Case

### Espace Admin
<div class="img-container-large">
  <img src="./images/use-case.ecpace.Admin.png" class="img-uml" alt="Use Case">
</div>

---
<!-- _class: uml-slide -->
## 3. Branche Fonctionnelle

### Global use case : Espace User
<div class="img-container-large">
  <img src="./images/use-case.user.ecpace.png" class="img-uml" alt="Use Case">
</div>

---
<!-- _class: uml-slide -->
## 3. Branche Fonctionnelle

### Global use case : Mobile
<div class="img-container-large">
  <img src="./images/usecase.mobile.png" class="img-uml" alt="Use Case">
</div>

---

## 4. Branche Technique : Tech Stack

<div class="sommaire-grid">
  <div class="dt-card" style="margin-top:0;">
    <h3>Architecture & Backend</h3>
    <ul style="font-size: 0.8em; line-height: 1.4;">
      <li><strong>MySQL :</strong> Base de données relationnelle.</li>
      <li><strong>Laravel 12 :</strong> Framework PHP Moderne.</li>
      <li><strong>Architecture N-Tiers :</strong> Séparation nette (Model, Service, Controller).</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:0; border-top-color: #3d8a54;">
    <h3>Frontend & Outils</h3>
    <ul style="font-size: 0.8em; line-height: 1.4;">
      <li><strong>Tailwind CSS :</strong> UI Utility-First.</li>
      <li><strong>Alpine.js :</strong> Réactivité légère.</li>
      <li><strong>AJAX / Vite :</strong> Performance et fluidité.</li>
    </ul>
  </div>
</div>

---
<!-- _class: uml-slide -->

## 5. Conception : Diagramme de classe

<div class="img-container-large">
  <img src="./images/diagramme-class.png" class="img-uml" alt="Diagramme de classe">
</div>

---
<!-- _class: uml-slide -->

## 6. Réalisation : L'architecture du projet

<div class="img-container-large">
  <img src="./images/l’architecture du projet.png" class="img-uml" alt="l’architecture du projet">
</div>

---

<!-- _class: lead -->

#  7. Démonstration Live
### Présentation interactive en temps réel de l'application ProMatch

---

##  8. Conclusion

<div class="conclusion-single-card">
  <p>
    Le projet <strong>ProMatch</strong> répond avec succès aux défis de la gestion de terrains de sport en automatisant les réservations en temps réel et en sécurisant les accès. Cette expérience a permis de concrétiser nos compétences techniques (Laravel 12, MySQL, Alpine, Tailwind) et agiles (Scrum, Design Thinking), tout en ouvrant la voie à des évolutions futures telles que l'intégration du paiement en ligne et la gestion de tournois.
  </p>
</div>

---

<!-- _class: lead -->

# Merci pour votre attention !
### Des questions ?

---
marp: true
theme: default
_class: lead
_paginate: false
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
  .logo-header img { height: 55px; }

  /* Summary Grid */
  .sommaire-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
  }
  .sommaire-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-left: 6px solid #4da565;
    padding: 18px 25px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease;
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
  .sommaire-text { font-weight: 600; color: #1e293b; }

  .img-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 70%;
  }
  .img-methodo {
    max-width: 90%;
    max-height: 400px;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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
  <img src="images/ofppt-logo.png" alt="OFPPT">
  <img src="images/logo-solicode.png" alt="Solicode">
</div>

# ProMatch
### Système Intelligent de Gestion de Terrains

**Réalisé par :** Adnane Kesksu
**Encadré par :** M. ESSARRAJ Fouad  
**Filière :** Développement Mobile et Web

---

## 📋 Sommaire

<div class="sommaire-grid">
  <div class="sommaire-item"><div class="sommaire-num">1</div><div class="sommaire-text">Contexte du projet</div></div>
  <div class="sommaire-item"><div class="sommaire-num">2</div><div class="sommaire-text">Méthodologie</div></div>
  <div class="sommaire-item"><div class="sommaire-num">3</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">4</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">5</div><div class="sommaire-text">Conception (UML)</div></div>
  <div class="sommaire-item"><div class="sommaire-num">6</div><div class="sommaire-text">Démonstration Live</div></div>
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

### 1. DESIGN THINKING : Empathie

<div class="img-container">
  <img src="./images/Image-carte-empatie.png" class="img-methodo" alt="Carte d'empathie">
</div>


---

### 2. DESIGN THINKING : Définition

<div class="sommaire-grid">
  <div class="dt-card">
    <h3 style="color: #ef4444; margin-bottom: 20px;">Le Constat (Frictions)</h3>
    <ul style="list-style-type: none; padding-left: 0; font-size: 0.85em;">
      <li style="margin-bottom: 12px;">❌ <strong>Gestion Archaïque :</strong> 100% manuelle, risques de doublons.</li>
      <li style="margin-bottom: 12px;">❌ <strong>Communication Lente :</strong> Dépendance au téléphone.</li>
      <li style="margin-bottom: 12px;">❌ <strong>Zéro Visibilité :</strong> Pas de vue en temps réel.</li>
      <li style="margin-bottom: 12px;">❌ <strong>Insécurité :</strong> Pas de preuve d'achat/CNI.</li>
    </ul>
  </div>
  <div class="dt-card" style="border-top-color: #10b981;">
    <h3 style="color: #10b981; margin-bottom: 20px;">Notre Solution</h3>
    <ul style="list-style-type: none; padding-left: 0; font-size: 0.85em;">
      <li style="margin-bottom: 12px;">✅ <strong>Digitalisation :</strong> Centralisation de l'offre.</li>
      <li style="margin-bottom: 12px;">✅ <strong>Self-Service :</strong> Réservation instantanée 24/7.</li>
      <li style="margin-bottom: 12px;">✅ <strong>Fiabilité :</strong> Vérification CNI obligatoire.</li>
      <li style="margin-bottom: 12px;">✅ <strong>Suivi :</strong> Dashboard complet pour l'Admin.</li>
    </ul>
  </div>
</div>


---

### 3. DESIGN THINKING : Idéation

<div class="sommaire-grid">
  <div class="dt-card" style="border-top-color: #f59e0b;">
    <h3 style="color: #f59e0b; margin-bottom: 20px;">Stratégie Digitale</h3>
    <ul style="list-style-type: none; padding-left: 0; font-size: 0.85em;">
      <li style="margin-bottom: 12px;">💡 <strong>Interface Web/Mobile :</strong> Accessibilité universelle.</li>
      <li style="margin-bottom: 12px;">💡 <strong>Validation CNI :</strong> Sécurisation du processus.</li>
      <li style="margin-bottom: 12px;">💡 <strong>Optimisation :</strong> Gestion intelligente des créneaux.</li>
    </ul>
  </div>
  <div class="dt-card" style="border-top-color: #3b82f6;">
     <h3 style="color: #3b82f6; margin-bottom: 20px;">Expérience Utilisateur</h3>
    <p style="font-size: 0.9em; line-height: 1.6;">Transformation d'un processus verbal en une expérience numérique fluide, sécurisée et valorisante pour le complexe sportif.</p>
  </div>
</div>


---

## 3. Branche Fonctionnelle

### Architecture des Cas d'utilisation
<div class="img-container">
  <img src="./images/use-case.png" class="img-methodo" alt="Use Case">
</div>


---

## Maquettes Haute Fidélité (UI/UX)

<div class="sommaire-grid">
  <div class="img-container">
    <h3 style="font-size: 0.9em; margin-bottom: 10px;">Interface Public (Desktop)</h3>
    <img src="./images/maquete.png" class="img-methodo" alt="Desktop Mockup">
  </div>
  <div class="img-container">
    <h3 style="font-size: 0.9em; margin-bottom: 10px;">Interface Mobile (Terrain)</h3>
    <img src="./images/mobile.png" class="img-methodo" alt="Mobile Mockup">
  </div>
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

## 5. Conception : Modélisation (UML)

<div class="img-container">
  <img src="./images/diagramme-class.png" class="img-methodo" alt="Diagramme de classe">

---

## 6. Démonstration & Outils

<div class="sommaire-grid">
  <div class="dt-card">
    <h3>Workflow de Dev</h3>
    <ul style="font-size: 0.8em;">
      <li><strong>IDE :</strong> VS Code & Antigravity.</li>
      <li><strong>Git :</strong> Gestion de version (GitHub).</li>
    </ul>
  </div>
  <div class="dt-card" style="border-top-color: #3d8a54;">
    <h3>Outils Analyse</h3>
    <ul style="font-size: 0.8em;">
      <li><strong>UML :</strong> Mermaid / PlantUML.</li>
      <li><strong>DB :</strong> MySQL Workbench.</li>
    </ul>
  </div>
</div>


---

<!-- _class: lead -->

# Merci pour votre attention !
### Des questions ?


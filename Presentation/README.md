---
marp: true
theme: default
_class: lead
_paginate: false
paginate: true
backgroundColor: #ffffff
style: |
  section {
    font-size: 22px;
    color: #333;
    line-height: 1.6;
    padding: 60px 80px;
  }
  footer { width: 100%; text-align: right; font-size: 14px; color: #888; }
  .logo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    top: 40px;   
    left: 60px;
    right: 60px;
  }
  .logo-header img { height: 140px; margin: 0; margin-left:10px; margin-right:10px }
  h1 { color: #088dc7; font-size: 2.8em; margin-top: 100px; text-align: left; }
  h2 { color: #088dc7; font-size: 2em; border-bottom: 2px solid #088dc7; margin-bottom: 40px;}
  h3 { text-align: left; color: #444; margin-top: 0; }

  .sommaire-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
  }
  .sommaire-item {
    display: flex;
    align-items: center;
    background: #f4faff;
    border-radius: 12px;
    padding: 15px 20px;
    border-left: 5px solid #088dc7;
  }
  .sommaire-num {
    background: #088dc7; color: white; width: 35px; height: 35px;
    display: flex; justify-content: center; align-items: center;
    border-radius: 50%; font-weight: bold; margin-right: 15px; flex-shrink: 0;
  }

  .img-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
  }
  .img-methodo {
    width: 85%;
    height: auto;
    max-height: 450px;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  .dt-card {
    background: #f0f7fa;
    padding: 30px;
    border-radius: 10px;
    border-top: 6px solid #088dc7;
    text-align: left;
    margin-top: 20px;
    width: 100%;
  }

  /* --- FIX COULEURS TECH STACK --- */
  .tech-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
  }
  .badge-simple {
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 600;
    background-color: #545353ff; /* Gris foncé unique */
    color: #ffffff !important;
    font-size: 0.85em;
    border: 1px solid #222;
  }
  .maquette-grid {
    display: flex;
    gap: 15px;
    justify-content: center;
    align-items: flex-start;
    height: 350px;
  }
---

<div class="logo-header">
  <img src="images/ofppt-logo.png" alt="Logo Left">
  <img src="images/logo-solicode.png" alt="Logo Right">
</div>

# **Projet de Fin de Formation**

### \*\* Système de Gestion des Terrains de Sport

**Réalisé par :** Adnane Kesksu
**Encadré par :** M. ESSARRAJ Fouad  
**Filière :** Développement Mobile et Web

---

## Sommaire

<div class="sommaire-grid">
  <div class="sommaire-item"><div class="sommaire-num">1</div><div class="sommaire-text">Contexte du projet</div></div>
  <div class="sommaire-item"><div class="sommaire-num">2</div><div class="sommaire-text">Méthodologie de travail</div></div>
  <div class="sommaire-item"><div class="sommaire-num">3</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">4</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">5</div><div class="sommaire-text">Conception</div></div>
  <div class="sommaire-item"><div class="sommaire-num">6</div><div class="sommaire-text">Démonstration</div></div>
  <div class="sommaire-item"><div class="sommaire-num">7</div><div class="sommaire-text">Conclusion</div></div>
</div>

---

## 1. Contexte du projet

<div class="img-container">
  <img src="./images/context.png" class="img-methodo" alt="contexy">
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
    <h3 style="color: #e74c3c; margin-bottom: 20px;">Le Constat (Frictions)</h3>
    <ul style="list-style-type: none; padding-left: 0;">
      <li style="margin-bottom: 15px;">❌ <strong>Gestion Archaïque :</strong> 100% manuelle, risques de doublons et erreurs.</li>
      <li style="margin-bottom: 15px;">❌ <strong>Communication Lente :</strong> Dépendance totale à l'intermédiaire téléphonique.</li>
      <li style="margin-bottom: 15px;">❌ <strong>Zéro Visibilité :</strong> Aucune vue en temps réel sur les disponibilités.</li>
      <li style="margin-bottom: 15px;">❌ <strong>Insécurité :</strong> Pas de preuve d'achat et risque élevé d'absentéisme.</li>
    </ul>
  </div>
  <div class="dt-card" style="border-top-color: #27ae60;">
    <h3 style="color: #27ae60; margin-bottom: 20px;">Notre Solution</h3>
    <ul style="list-style-type: none; padding-left: 0;">
      <li style="margin-bottom: 15px;">✅ <strong>Digitalisation :</strong> Centralisation de l'offre et de la demande.</li>
      <li style="margin-bottom: 15px;">✅ <strong>Self-Service :</strong> Réservation instantanée sans intervention humaine.</li>
      <li style="margin-bottom: 15px;">✅ <strong>Fiabilité :</strong> Vérification de CNI et confirmations numériques.</li>
      <li style="margin-bottom: 15px;">✅ <strong>Suivi :</strong> Tableau de bord pour les revenus et l'historique.</li>
    </ul>
  </div>
</div>

---

### 3. DESIGN THINKING : Idéation

<div class="sommaire-grid">
  <div class="dt-card" style="border-top-color: #f39c12;">
    <h3 style="color: #f39c12; margin-bottom: 20px;">Stratégie Digitale</h3>
    <ul style="list-style-type: none; padding-left: 0;">
      <li style="margin-bottom: 15px;">💡 <strong>Disponibilité 24/7 :</strong> Plateforme en ligne pour éliminer les barrières temporelles.</li>
      <li style="margin-bottom: 15px;">💡 <strong>Validation CNI :</strong> Processus sécurisé avec approbation administrateur.</li>
      <li style="margin-bottom: 15px;">💡 <strong>Gestion Mobile :</strong> Interface optimisée pour les réservations sur le terrain.</li>
      <li style="margin-bottom: 15px;">💡 <strong>Analyse Métier :</strong> Dashboard pour optimiser l'occupation des terrains.</li>
    </ul>
  </div>
  <div class="dt-card" style="border-top-color: #088dc7;">
     <h3 style="color: #088dc7; margin-bottom: 20px;">Expérience Utilisateur</h3>
    <p>Transformation d'un processus verbal et incertain en une expérience numérique fluide, sécurisée et valorisante pour le complexe sportif.</p>
  </div>
</div>

---


## Branche Fonctionnelle : Cas d'utilisation

### Global Use Case

<div class="img-container">
  <h3>Interaction Utilisateur (UML)</h3>
  <img src="./images/use-case.png" class="img-methodo" alt="Use Case">
</div>

---

## Branche Fonctionnelle : Cas d'utilisation

### Sprint 1 :

<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="./images/sprint1.png" alt="usecas" style="max-height: 350px;">
  </div>
</div>

---

## Branche Fonctionnelle : Cas d'utilisation

### sprint 2:

 <div class="maquette-grid">
  <div style="text-align: center;">
    <img src="./images/sprint2.png" alt="usecas" style="max-height: 350px;">
  </div>
</div>

---

## Branche Fonctionnelle : Maquettes (UI/UX)

<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="./images/maquete.png" alt="usecas" style="max-height: 350px;">
  </div>
</div>

---

## Branche Fonctionnelle : Maquettes (UI/UX) Mobile

<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="./images/mobile.png" alt="usecas" style="max-height: 350px;">
  </div>
</div>



---

## 4. Branche Technique : Tech Stack

<div class="sommaire-grid">
  <div class="dt-card" style="margin-top:0;">
    <h4>Les technologies à utiliser</h4>
    <ul>
      <li><strong>Base de données:</strong> MySQL</li>
      <li><strong>Framework:</strong> Laravel 12</li>
      <li><strong>Architecture N-Tiers:</strong>
        <ul style="margin-top: 5px;">
          <li>Controller: Requêtes HTTP</li>
          <li>Service: Logique métier</li>
          <li>Model: Base de données</li>
        </ul>
      </li>
      <li><strong>Architecture MVC</strong></li>
      <li><strong>Blade:</strong> Templates réutilisables</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:0; border-top-color: #27ae60;">
    <ul>
      <li><strong>AJAX:</strong> Interactions dynamiques sans rechargement</li>
      <li><strong>Alpine.js:</strong> Librairie JavaScript dynamique</li>
      <li><strong>Spatie:</strong> Gestion permissions et rôles</li>
      <li><strong>Vite:</strong> Outil de build rapide</li>
      <li><strong>Lucide:</strong> Librairie d'icônes</li>
      <li><strong>Tailwind CSS:</strong> Développement responsive</li>
    </ul>
  </div>
</div>

---

## 5. Conception : Diagramme de classe

<h3>Modélisation des données (MLD)</h3>
<div class="img-container">
  <img src="./images/diagramme-class.png" class="img-methodo" alt="Diagramme de classe">
</div>

---

## 6. Démonstration : Environnement & Outils

<div class="sommaire-grid">
  <div class="dt-card" style="margin-top:0;">
    <h4>Environnement de Développement</h4>
    <ul>
      <li><strong>IDE:</strong> VS Code & Antigravity</li>
      <li><strong>Monitoring DB:</strong> Workbench SQL</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:0; border-top-color: #27ae60;">
    <h4>Gestion & Déploiement</h4>
    <ul>
      <li><strong>Modélisation UML:</strong> Mermaid/PlantUML</li>
      <li><strong>Gestion de version:</strong> Git (GitHub)</li>
      <li><strong>Navigateur:</strong> Chrome DevTools</li>
    </ul>
  </div>
</div>

---

## 7. Conclusion

### Merci pour votre attention !

/**
 * Advanced Component Loader v2.0
 * Supports nested components, props, and atomic design structure
 */

const COMPONENTS_BASE_URL = (() => {
  try {
    const src = document.currentScript?.src;
    if (src) return new URL("./", src);
  } catch (_) {}
  return new URL("./components-v2/", window.location.href);
})();

// Component registry for caching
const componentCache = new Map();

/**
 * Load a component from file or cache
 */
async function loadComponentHTML(name) {
  if (componentCache.has(name)) {
    return componentCache.get(name);
  }

  // Try different paths: atoms, molecules, organisms
  const paths = [
    `atoms/${name}.html`,
    `molecules/${name}.html`,
    `organisms/${name}.html`,
    `${name}.html`
  ];

  for (const path of paths) {
    try {
      const res = await fetch(new URL(path, COMPONENTS_BASE_URL));
      if (res.ok) {
        const html = await res.text();
        componentCache.set(name, html);
        return html;
      }
    } catch (e) {
      continue;
    }
  }

  throw new Error(`Component not found: ${name}`);
}

/**
 * Replace placeholders with data attributes
 */
function replacePlaceholders(html, dataset) {
  let result = html;
  
  Object.entries(dataset).forEach(([key, val]) => {
    // Replace [[key]] with value
    const placeholder = `[[${key}]]`;
    result = result.replaceAll(placeholder, val || '');
  });

  // Remove any remaining placeholders
  result = result.replace(/\[\[[\w-]+\]\]/g, '');
  
  return result;
}

/**
 * Load all components recursively
 */
async function loadComponents() {
  // Check for file:// protocol
  if (window.location.protocol === 'file:') {
    console.error("CORS Error: Use a local server (Live Server, python -m http.server, etc.)");
    const warning = document.createElement("div");
    warning.style.cssText = "background: #f82; color: #fff; padding: 10px; text-align: center; border-radius: 4px; margin: 10px; font-weight: bold; position: sticky; top: 0; z-index: 9999;";
    warning.innerText = "⚠️ Components require a local server. Use Live Server or similar.";
    document.body.prepend(warning);
    return;
  }

  const MAX_PASSES = 10;
  
  for (let pass = 0; pass < MAX_PASSES; pass++) {
    const elements = [
      ...document.querySelectorAll("[data-component]:not([data-component-loaded]):not([data-component-failed])"),
    ];
    
    if (elements.length === 0) break;

    let replacedAny = false;

    for (const el of elements) {
      const name = el.dataset.component;
      
      try {
        let html = await loadComponentHTML(name);
        html = replacePlaceholders(html, el.dataset);

        const tmp = document.createElement("div");
        tmp.innerHTML = html;
        
        el.replaceWith(...tmp.childNodes);
        replacedAny = true;
      } catch (e) {
        console.warn(`Failed to load component "${name}":`, e);
        el.setAttribute("data-component-failed", "true");
        el.textContent = `[Component Error: ${name}]`;
      }
    }

    if (!replacedAny) break;
  }

  // Highlight active nav links
  highlightActiveLinks();
}

/**
 * Highlight active navigation links
 */
function highlightActiveLinks() {
  const currentPath = location.pathname.split("/").pop() || "index.html";
  
  document.querySelectorAll("a[href]").forEach((link) => {
    const href = link.getAttribute("href");
    if (href === currentPath || href === `./${currentPath}`) {
      link.classList.add("active");
    }
  });
}

// Auto-load on DOM ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", loadComponents);
} else {
  loadComponents();
}

// Export for manual usage
window.ComponentLoader = {
  load: loadComponents,
  loadComponent: loadComponentHTML,
  cache: componentCache
};

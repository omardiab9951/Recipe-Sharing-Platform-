const AppUtils = {
  $: function(selector) {
    return document.querySelector(selector);
  },
  
  $all: function(selector) {
    return Array.from(document.querySelectorAll(selector));
  },
  
  createEl: function(tag, attrs = {}, children = []) {
    const el = document.createElement(tag);
    Object.entries(attrs).forEach(([k, v]) => {
      if (k === 'class') el.className = v;
      else if (k === 'dataset') Object.assign(el.dataset, v);
      else el.setAttribute(k, v);
    });
    children.forEach(c => {
      if (typeof c === 'string') {
        el.appendChild(document.createTextNode(c));
      } else {
        el.appendChild(c);
      }
    });
    return el;
  },
  
  qsExists: function(selector) {
    return document.querySelector(selector) !== null;
  },
  
  debounce: function(fn, ms = 300) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => fn.apply(this, args), ms);
    };
  },
  
  sanitizeHTML: function(str) {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
  }
};
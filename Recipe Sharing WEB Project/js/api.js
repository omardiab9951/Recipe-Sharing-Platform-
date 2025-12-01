const AppAPI = {
  fetchWithTimeout: function(resource, options = {}) {
    const controller = new AbortController();
    const id = setTimeout(() => controller.abort(), AppConfig.TIMEOUT);
    return fetch(resource, {...options, signal: controller.signal})
      .finally(() => clearTimeout(id));
  },
  
  get: async function(endpoint) {
    try {
      const res = await this.fetchWithTimeout(AppConfig.API_BASE_URL + endpoint, {
        method: 'GET',
        credentials: 'same-origin'
      });
      return await res.json();
    } catch (err) {
      console.error('API GET Error:', err);
      throw err;
    }
  },
  
  post: async function(endpoint, body) {
    const opts = { 
      method: 'POST', 
      credentials: 'same-origin' 
    };
    
    if (body instanceof FormData) {
      opts.body = body;
    } else {
      opts.headers = { 'Content-Type': 'application/json' };
      opts.body = JSON.stringify(body);
    }
    
    try {
      const res = await this.fetchWithTimeout(AppConfig.API_BASE_URL + endpoint, opts);
      return await res.json();
    } catch (err) {
      console.error('API POST Error:', err);
      throw err;
    }
  }
};

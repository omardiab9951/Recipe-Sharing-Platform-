import { API_BASE_URL, TIMEOUT } from './config.js';


function fetchWithTimeout(resource, options = {}) {
const controller = new AbortController();
const id = setTimeout(() => controller.abort(), TIMEOUT);
return fetch(resource, {...options, signal: controller.signal}).finally(() => clearTimeout(id));
}


export async function apiGet(endpoint) {
const res = await fetchWithTimeout(API_BASE_URL + endpoint, { method: 'GET', credentials: 'same-origin' });
return await res.json();
}


export async function apiPost(endpoint, body) {
const opts = { method: 'POST', credentials: 'same-origin' };
// if body is FormData, don't set headers
if (body instanceof FormData) opts.body = body;
else {
opts.headers = { 'Content-Type': 'application/json' };
opts.body = JSON.stringify(body);
}
const res = await fetchWithTimeout(API_BASE_URL + endpoint, opts);
return await res.json();
}
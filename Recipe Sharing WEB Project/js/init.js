import * as AddRecipe from './pages/add-recipe.js';
import * as EditRecipe from './pages/edit-recipe.js';
import * as Home from './pages/home.js';
import * as RecipeDetail from './pages/recipe-detail.js';
import * as Profile from './pages/profile.js';


export function initPageScripts() {
const body = document.body;
if (body.classList.contains('page-add-recipe')) AddRecipe.init();
if (body.classList.contains('page-edit-recipe')) EditRecipe.init();
if (body.classList.contains('page-home')) Home.init();
if (body.classList.contains('page-recipe-detail')) RecipeDetail.init();
if (body.classList.contains('page-profile')) Profile.init();
}



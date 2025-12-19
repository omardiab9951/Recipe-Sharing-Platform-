-- ================================================
-- SAMPLE DATA FOR RECIPE SHARING PLATFORM
-- Insert Admin Account + 15 Recipes
-- ================================================

-- 1. INSERT ADMIN ACCOUNT
-- Email: sut.edu.eg@gmail.com
-- Password: Sut12345 (hashed with bcrypt)
INSERT INTO users (name, email, password_hash, created_at) VALUES 
('Admin', 'sut.edu.eg@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());

-- Get admin user_id (usually 1, but let's be safe)
SET @admin_id = LAST_INSERT_ID();

-- 2. INSERT 15 SAMPLE RECIPES

-- Recipe 1: Classic Spaghetti Bolognese
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Classic Spaghetti Bolognese', 'Dinner', 
'500g ground beef
1 onion, diced
2 cloves garlic, minced
1 can (400g) crushed tomatoes
2 tbsp tomato paste
1 tsp dried oregano
1 tsp dried basil
400g spaghetti
Salt and pepper to taste
Parmesan cheese for serving',
'1. Heat olive oil in a large pan over medium heat
2. Add diced onion and garlic, cook until softened (3-4 minutes)
3. Add ground beef and cook until browned, breaking it up as it cooks
4. Stir in tomato paste, crushed tomatoes, oregano, and basil
5. Season with salt and pepper, let simmer for 20-25 minutes
6. Meanwhile, cook spaghetti according to package directions
7. Drain pasta and serve with bolognese sauce on top
8. Garnish with fresh Parmesan cheese and basil',
'default-recipe.jpg', NOW());

-- Recipe 2: Spicy Chicken Curry
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Spicy Chicken Curry', 'Dinner',
'750g chicken breast, cubed
2 tbsp curry powder
1 onion, chopped
3 cloves garlic, minced
1 can coconut milk
2 tomatoes, diced
1 tbsp ginger, grated
2 tbsp vegetable oil
Fresh cilantro
Rice for serving',
'1. Heat oil in a large pot over medium-high heat
2. Add chicken and brown on all sides, remove and set aside
3. Add onion, garlic, and ginger to the pot, sauté for 3 minutes
4. Stir in curry powder and cook for 1 minute until fragrant
5. Add tomatoes and cook until softened
6. Pour in coconut milk and bring to a simmer
7. Return chicken to pot and simmer for 20-25 minutes
8. Serve over steamed rice and garnish with fresh cilantro',
'default-recipe.jpg', NOW());

-- Recipe 3: Fresh Vegan Quinoa Salad
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Fresh Vegan Quinoa Salad', 'Lunch',
'1 cup quinoa, cooked and cooled
1 cucumber, diced
1 cup cherry tomatoes, halved
1 red bell pepper, diced
1/4 cup red onion, finely chopped
1/4 cup fresh parsley, chopped
Juice of 2 lemons
3 tbsp olive oil
Salt and pepper to taste',
'1. Cook quinoa according to package directions and let cool completely
2. In a large bowl, combine cooled quinoa, cucumber, tomatoes, bell pepper, and red onion
3. In a small bowl, whisk together lemon juice, olive oil, salt, and pepper
4. Pour dressing over quinoa mixture and toss well
5. Add fresh parsley and mix thoroughly
6. Refrigerate for at least 30 minutes before serving
7. Serve chilled as a light lunch or side dish',
'default-recipe.jpg', NOW());

-- Recipe 4: Chocolate Lava Cake
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Chocolate Lava Cake', 'Dessert',
'120g dark chocolate, chopped
120g butter
2 eggs
2 egg yolks
1/4 cup sugar
2 tbsp flour
Pinch of salt
Vanilla ice cream for serving',
'1. Preheat oven to 425°F (220°C) and butter 4 ramekins
2. Melt chocolate and butter together in a double boiler
3. In a separate bowl, whisk eggs, egg yolks, and sugar until thick
4. Fold melted chocolate into egg mixture
5. Gently fold in flour and salt
6. Divide batter among ramekins
7. Bake for 12-14 minutes until edges are firm but center is soft
8. Let cool for 1 minute, then invert onto plates
9. Serve immediately with vanilla ice cream',
'default-recipe.jpg', NOW());

-- Recipe 5: Classic Pancakes
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Classic Fluffy Pancakes', 'Breakfast',
'2 cups all-purpose flour
2 tbsp sugar
2 tsp baking powder
1/2 tsp salt
2 eggs
1 3/4 cups milk
1/4 cup melted butter
Maple syrup and berries for serving',
'1. In a large bowl, whisk together flour, sugar, baking powder, and salt
2. In another bowl, beat eggs then add milk and melted butter
3. Pour wet ingredients into dry ingredients and mix until just combined
4. Heat a griddle or pan over medium heat and lightly grease
5. Pour 1/4 cup batter for each pancake onto the griddle
6. Cook until bubbles form on surface, then flip
7. Cook until golden brown on both sides
8. Serve warm with maple syrup and fresh berries',
'default-recipe.jpg', NOW());

-- Recipe 6: Caesar Salad
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Classic Caesar Salad', 'Lunch',
'1 large romaine lettuce, chopped
1/2 cup grated Parmesan cheese
1 cup croutons
For dressing:
3 cloves garlic, minced
2 tsp Dijon mustard
2 tsp Worcestershire sauce
2 tsp lemon juice
1/2 cup olive oil
Salt and pepper',
'1. Make dressing: whisk together garlic, mustard, Worcestershire, and lemon juice
2. Slowly drizzle in olive oil while whisking constantly
3. Season with salt and pepper
4. Place chopped romaine in a large bowl
5. Add half the Parmesan cheese and toss with dressing
6. Add croutons and remaining Parmesan
7. Toss once more and serve immediately',
'default-recipe.jpg', NOW());

-- Recipe 7: Beef Tacos
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Beef Tacos', 'Dinner',
'500g ground beef
1 packet taco seasoning
8 taco shells
1 cup shredded lettuce
1 cup diced tomatoes
1 cup shredded cheddar cheese
1/2 cup sour cream
Salsa for serving',
'1. Brown ground beef in a large skillet over medium-high heat
2. Drain excess fat and add taco seasoning with water as directed on packet
3. Simmer for 5-7 minutes until sauce thickens
4. Warm taco shells according to package directions
5. Fill each shell with seasoned beef
6. Top with lettuce, tomatoes, and cheese
7. Add a dollop of sour cream and salsa
8. Serve immediately while warm',
'default-recipe.jpg', NOW());

-- Recipe 8: Margherita Pizza
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Margherita Pizza', 'Dinner',
'1 pizza dough
1 cup tomato sauce
250g fresh mozzarella, sliced
Fresh basil leaves
2 tbsp olive oil
2 cloves garlic, minced
Salt to taste',
'1. Preheat oven to 475°F (245°C) with pizza stone inside
2. Roll out pizza dough on a floured surface
3. Mix tomato sauce with minced garlic
4. Spread sauce evenly over dough
5. Arrange mozzarella slices on top
6. Drizzle with olive oil and sprinkle with salt
7. Carefully transfer to hot pizza stone
8. Bake for 12-15 minutes until crust is golden
9. Remove from oven and top with fresh basil leaves
10. Slice and serve hot',
'default-recipe.jpg', NOW());

-- Recipe 9: Greek Yogurt Parfait
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Greek Yogurt Parfait', 'Breakfast',
'2 cups Greek yogurt
1 cup granola
1 cup mixed berries (strawberries, blueberries, raspberries)
2 tbsp honey
Fresh mint leaves for garnish',
'1. In serving glasses or bowls, add a layer of Greek yogurt
2. Add a layer of granola
3. Add a layer of mixed berries
4. Repeat layers until glass is filled
5. Drizzle honey on top
6. Garnish with fresh mint leaves
7. Serve immediately or refrigerate until ready to eat',
'default-recipe.jpg', NOW());

-- Recipe 10: Chicken Stir-Fry
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Chicken Stir-Fry', 'Dinner',
'500g chicken breast, sliced
2 cups mixed vegetables (bell peppers, broccoli, carrots)
3 tbsp soy sauce
1 tbsp oyster sauce
2 cloves garlic, minced
1 tbsp ginger, grated
2 tbsp vegetable oil
Rice for serving',
'1. Heat oil in a wok or large pan over high heat
2. Add chicken and cook until no longer pink, about 5-6 minutes
3. Remove chicken and set aside
4. Add more oil if needed and stir-fry vegetables for 3-4 minutes
5. Add garlic and ginger, cook for 30 seconds
6. Return chicken to pan
7. Add soy sauce and oyster sauce, toss everything together
8. Cook for 2 more minutes until heated through
9. Serve over steamed rice',
'default-recipe.jpg', NOW());

-- Recipe 11: French Toast
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Classic French Toast', 'Breakfast',
'8 slices bread (preferably day-old)
4 eggs
1 cup milk
2 tsp vanilla extract
1 tsp cinnamon
2 tbsp butter
Maple syrup and powdered sugar for serving',
'1. In a shallow bowl, whisk together eggs, milk, vanilla, and cinnamon
2. Heat butter in a large skillet over medium heat
3. Dip each bread slice in egg mixture, coating both sides
4. Cook bread slices for 2-3 minutes per side until golden brown
5. Transfer to a plate and keep warm
6. Repeat with remaining slices
7. Serve hot with maple syrup and a dusting of powdered sugar',
'default-recipe.jpg', NOW());

-- Recipe 12: Caprese Salad
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Caprese Salad', 'Lunch',
'4 large tomatoes, sliced
250g fresh mozzarella, sliced
Fresh basil leaves
3 tbsp olive oil
2 tbsp balsamic vinegar
Salt and pepper to taste',
'1. Arrange tomato and mozzarella slices alternating on a serving platter
2. Tuck fresh basil leaves between the slices
3. Drizzle with olive oil and balsamic vinegar
4. Season with salt and freshly ground black pepper
5. Let sit for 5-10 minutes to allow flavors to meld
6. Serve at room temperature as an appetizer or side dish',
'default-recipe.jpg', NOW());

-- Recipe 13: Banana Bread
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Moist Banana Bread', 'Dessert',
'3 ripe bananas, mashed
1/3 cup melted butter
3/4 cup sugar
1 egg, beaten
1 tsp vanilla extract
1 tsp baking soda
Pinch of salt
1 1/2 cups all-purpose flour',
'1. Preheat oven to 350°F (175°C) and grease a 9x5 loaf pan
2. In a large bowl, mix melted butter with mashed bananas
3. Stir in sugar, egg, and vanilla
4. Sprinkle baking soda and salt over mixture and mix well
5. Add flour and stir until just combined (don\'t overmix)
6. Pour batter into prepared loaf pan
7. Bake for 60-65 minutes until a toothpick comes out clean
8. Cool in pan for 10 minutes, then transfer to wire rack
9. Slice and serve warm or at room temperature',
'default-recipe.jpg', NOW());

-- Recipe 14: Vegetable Soup
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Hearty Vegetable Soup', 'Lunch',
'2 carrots, diced
2 celery stalks, diced
1 onion, chopped
2 potatoes, cubed
1 can diced tomatoes
4 cups vegetable broth
1 tsp dried thyme
1 bay leaf
Salt and pepper to taste
Fresh parsley for garnish',
'1. Heat oil in a large pot over medium heat
2. Add onion, carrots, and celery, sauté for 5 minutes
3. Add potatoes, tomatoes, and vegetable broth
4. Stir in thyme and bay leaf
5. Bring to a boil, then reduce heat and simmer for 25-30 minutes
6. Season with salt and pepper
7. Remove bay leaf before serving
8. Garnish with fresh parsley
9. Serve hot with crusty bread',
'default-recipe.jpg', NOW());

-- Recipe 15: Chocolate Chip Cookies
INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) VALUES 
(@admin_id, 'Perfect Chocolate Chip Cookies', 'Dessert',
'2 1/4 cups all-purpose flour
1 tsp baking soda
1 tsp salt
1 cup butter, softened
3/4 cup sugar
3/4 cup brown sugar
2 eggs
2 tsp vanilla extract
2 cups chocolate chips',
'1. Preheat oven to 375°F (190°C)
2. Combine flour, baking soda, and salt in a bowl
3. In another bowl, cream together butter and both sugars
4. Beat in eggs and vanilla
5. Gradually blend in flour mixture
6. Stir in chocolate chips
7. Drop rounded tablespoons of dough onto ungreased cookie sheets
8. Bake for 9-11 minutes until golden brown
9. Cool on baking sheet for 2 minutes before transferring to wire rack
10. Enjoy warm with a glass of milk',
'default-recipe.jpg', NOW());

-- 3. ADD SAMPLE COMMENTS
INSERT INTO comments (recipe_id, user_id, comment_text, created_at) VALUES
(1, @admin_id, 'This is my family\'s favorite! We make it every Sunday.', NOW()),
(2, @admin_id, 'The perfect level of spice - absolutely delicious!', NOW()),
(3, @admin_id, 'Light, fresh, and so healthy. Perfect for summer!', NOW());

-- Done!
SELECT 'Sample data inserted successfully!' AS Message;

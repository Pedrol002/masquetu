USE user_tn;

-- Atualizar categorias para os produtos de tênis existentes
UPDATE produto SET categoria = 'Masculino' WHERE nome LIKE '%Masculino%';
UPDATE produto SET categoria = 'Feminino' WHERE nome LIKE '%Feminino%';

-- Para produtos sem gênero específico, definir como 'Unissex' ou similar, mas aqui todos têm gênero

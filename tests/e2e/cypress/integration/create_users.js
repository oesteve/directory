describe('User management inteface page', () => {
  it('Visit the creation users page by url', () => {
    cy.visit('/create');
    cy.get('.card-title').should('contain.text', 'Crear un nuevo usuario');
  });

  it('Visit the creation users by navigation', () => {
    cy.visit('/');
    cy.get('a').contains('Añadir Usuarios').click();
    cy.get('.card-title').should('contain.text', 'Crear un nuevo usuario');
  });

  it('Create a new user', () => {
    cy.visit('/create');
    cy.get('input[name=name]').click().type('My User');
    cy.get('input[name=\'props.0.name\']').click().type('bar');
    cy.get('input[name=\'props.0.value\']').click().type('foo');
    cy.wait(500); // Wait to redux
    cy.contains('Guardar').click();

    cy.get('.alert').then((alert) => {
      const text = alert.text().trim();
      expect(text).to.contain('My User ha sido añadido');
      const [id] = text.match(/([a-z,0-9,\-]+)$/g);
      expect(id).not.be.null;

      // Clean
      cy.request('DELETE', `/api/user/${id}`);
    });
  });
});

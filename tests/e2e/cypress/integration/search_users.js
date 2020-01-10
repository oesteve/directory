describe('Search users interface', () => {
  it('Visit the search page', () => {
    cy.visit('/');
    cy.get('.card-title').should('contain.text', 'Buscador de usuarios');
  });


  it('Search users by props', () => {
    const userIds = [];

    cy.fixture('users.json').then((users) => {
      users.forEach((user) => {
        cy.request('POST', '/api/user', user)
          .then((response) => {
            expect(response.status).to.eq(200);
            userIds.push(response.body.id);
          });
      });
    });

    cy.get('input').click().type('azul');
    cy.get('#userList').children().should('have.length', 2);

    // Clean
    cy.wrap(userIds).each((id) => {
      cy.request('DELETE', `/api/user/${id}`);
    });
  });
});

describe('Pruebas completas del Administrador', () => {

  it('Valida panel y navegación real del admin', () => {
 
    cy.visit('/login')

    cy.get('input[name="email"]').type('jose.guzman.is@unipolidgo.edu.mx')
    cy.get('input[name="password"]').type('GabrielGuzman90Esparza')

    cy.get('button[type="submit"]').click()

    cy.visit('/admin')

    cy.contains('Panel principal')
    cy.contains('Medicamentos')
    cy.contains('Donaciones')
    cy.contains('Contactos')
    cy.contains('Usuarios')

    cy.visit('/admin/peticiones')
    cy.url().should('include', '/admin/peticiones')

    cy.get('table').should('exist')
    cy.contains('Lista de pedidos')

    cy.visit('/admin/peticiones/aceptados')
    cy.url().should('include', '/admin/peticiones/aceptados')

    cy.visit('/admin/peticiones/rechazados')
    cy.url().should('include', '/admin/peticiones/rechazados')

    cy.contains('Rechazado').should('exist')

  })

  it('Usuario normal no puede acceder al panel admin', () => {

  cy.visit('/login')

  cy.get('input[name="email"]').clear().type('userestandar@test.com')
  cy.get('input[name="password"]').clear().type('userEstandar1')

  cy.get('button[type="submit"]').click()

  cy.visit('/admin', { failOnStatusCode: false })

  cy.contains('403').should('exist')

})

})
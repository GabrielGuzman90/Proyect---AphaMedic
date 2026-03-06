describe('Pruebas completas del Administrador', () => {

  it('Valida panel y navegación real del admin', () => {
 
    cy.visit('/login')

    cy.get('input[name="email"]').type('Carlos404@gmail.com')
    cy.get('input[name="password"]').type('Carlos@404')

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

  cy.get('input[name="email"]').clear().type('juancarlosndd@gmail.com')
  cy.get('input[name="password"]').clear().type('Juan@404')

  cy.get('button[type="submit"]').click()

  cy.visit('/admin', { failOnStatusCode: false })

  cy.contains('403').should('exist')

})

})
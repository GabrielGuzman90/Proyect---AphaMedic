describe('Prueba de Solicitud de Medicamento', () => {

  it('Debería permitir a un usuario buscar un medicamento, agregarlo al carrito y enviar el pedido', () => {

    // Paso 1: Visitar login
    cy.visit('http://localhost:81/web3/dev/public/login');

    // Paso 2: Login
    cy.get('input[name="email"]').should('be.visible').type('routerqf2005@gmail.com');
    cy.get('input[name="password"]').should('be.visible').type('Roberto2005*');
    cy.get('button[type="submit"]').click();

    // Paso 3: Verificar redirección
    cy.url({ timeout: 10000 }).should('include', '/');

    // Paso 4: Ir a medicamentos
    cy.visit('http://localhost:81/web3/dev/public/medicamentos');

    // Paso 5: Esperar a que aparezcan los botones "Solicitar"
    cy.contains('button', 'Solicitar', { timeout: 10000 })
      .scrollIntoView()
      .should('be.visible');

    // Paso 6: Hacer clic en el primer botón "Solicitar"
    cy.contains('button', 'Solicitar')
      .scrollIntoView()
      .click();

    // Paso 7: Verificar toast
    // Paso 7: Verificar alert
    cy.on('window:alert', (text) => {
      expect(text).to.contains('Solicitud enviada correctamente');
      });

    // Paso 8: Ir al carrito (mejor usar contains por si la ruta cambia)
    // Paso 8: Ir al carrito
    cy.get('a.icon-btn[href*="cart"]')
      .first()
      .scrollIntoView()
      .click();

    // Paso 9: Esperar que cargue el carrito
    cy.url().should('include', 'cart');

    // Paso 10: Llenar formulario
    cy.get('input[name="nombre"]').should('be.visible').type('Roberto Quiñones');
    cy.get('input[name="telefono"]').type('6182685063');
    cy.get('input[name="correo"]').type('routerqf2005@gmail.com');

    // Paso 11: Enviar pedido
    cy.contains('button', 'Enviar Pedido')
      .scrollIntoView()
      .click();

   
  });

});
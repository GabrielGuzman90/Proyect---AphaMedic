describe('Prueba de Login', () => {

  // Caso de prueba para login con credenciales válidas
  it('Debería permitir el inicio de sesión con credenciales válidas', () => {
    // Paso 1: Visitar la página de inicio de sesión
    cy.visit('http://localhost:81/web3/dev/public/login'); 
    // Paso 2: Escribir el correo electrónico
    cy.get('input[name="email"]').type('routerqf2005@gmail.com'); 
    // Paso 3: Escribir la contraseña
    cy.get('input[name="password"]').type('Roberto2005*'); 
    // Paso 4: Hacer clic en el botón de inicio de sesión
    cy.get('button[type="submit"]').click();
    
    cy.url().then((url) => {
    cy.log(url)
    })
    // Paso 5: Verificar que la URL cambie después de iniciar sesión 
    cy.url().should('include', '/'); // Cambia la URL de acuerdo a lo que espera tu aplicación
    // Paso 6: Verificar que el mensaje de bienvenida esté presente
    cy.get('h1').should('contain', ''); 
  });
  // Caso de prueba para login con credenciales incorrectas
  it('No debería permitir el login con credenciales incorrectas', () => {
    // Paso 1: Visitar la página de inicio de sesión
    cy.visit('http://localhost:81/web3/dev/public/login'); 
    // Paso 2: Intentar iniciar sesión con credenciales incorrectas
    cy.get('input[name="email"]').type('routerqf2005@gmail.com'); 
    cy.get('input[name="password"]').type('Rrto2005*'); 
    cy.get('button[type="submit"]').click(); 
    // Paso 3: Verificar que la URL no cambie (ya que el login falló)
    cy.url().should('include', '/login');
  });

});
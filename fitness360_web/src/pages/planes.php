<section>
  <div class="container">
    <h2>Elige tu plan</h2>
    <table class="features-table" id="tabla-planes">
      <tr>
        <th></th>
        <th class="plan plan-highlight" data-plan="basico">
          <img src="img/plan-basico.jpg" alt="Plan Básico"><br>
          Plan Básico
        </th>
        <th class="plan" data-plan="premium">
          <img src="img/plan-premium.jpg" alt="Plan Premium"><br>
          Plan Premium
        </th>
        <th class="plan" data-plan="platinum">
          <img src="img/plan-platinum.jpg" alt="Plan Platinum"><br>
          Plan Platinum
        </th>
      </tr>
      <tr>
        <td>Acceso al gimnasio</td>
        <td data-plan="basico" class="plan-highlight"><span class="check">&#10003;</span></td>
        <td data-plan="premium"><span class="check">&#10003;</span></td>
        <td data-plan="platinum"><span class="check">&#10003;</span></td>
      </tr>
      <tr>
        <td>Rutinas generales</td>
        <td data-plan="basico" class="plan-highlight"><span class="check">&#10003;</span></td>
        <td data-plan="premium"><span class="check">&#10003;</span></td>
        <td data-plan="platinum"><span class="check">&#10003;</span></td>
      </tr>
      <tr>
        <td>Entrenador personal</td>
        <td data-plan="basico" class="plan-highlight"><span class="cross">&#10007;</span></td>
        <td data-plan="premium"><span class="check">&#10003;</span></td>
        <td data-plan="platinum"><span class="check">&#10003;</span></td>
      </tr>
      <tr>
        <td>Dieta personalizada</td>
        <td data-plan="basico" class="plan-highlight"><span class="cross">&#10007;</span></td>
        <td data-plan="premium"><span class="check">&#10003;</span></td>
        <td data-plan="platinum"><span class="check">&#10003;</span></td>
      </tr>
      <tr>
        <td>App móvil</td>
        <td data-plan="basico" class="plan-highlight"><span class="cross">&#10007;</span></td>
        <td data-plan="premium"><span class="check">&#10003;</span></td>
        <td data-plan="platinum"><span class="check">&#10003;</span></td>
      </tr>
      <tr>
        <td>Precio</td>
        <td data-plan="basico" class="plan-highlight"><strong>L. 650.00</strong>/mes</td>
        <td data-plan="premium"><strong>L. 750.00</strong>/mes</td>
        <td data-plan="platinum"><strong>L. 850.00</strong>/mes</td>
      </tr>
    </table>
    <div style="text-align:center; margin-top:30px;">
      <button id="btn-inscribirse" class="btn btn-primary" disabled>Seleccionar este plan</button>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let selectedPlan = null;
  const table = document.getElementById('tabla-planes');
  const btn = document.getElementById('btn-inscribirse');

  // Selecciona la columna al hacer click en el encabezado
  table.querySelectorAll('th[data-plan]').forEach((th, idx) => {
    th.addEventListener('click', function() {
      // Quitar selección previa
      table.querySelectorAll('th[data-plan], td[data-plan]').forEach(cell => {
        cell.classList.remove('selected-plan');
      });
      // Seleccionar columna
      const plan = th.getAttribute('data-plan');
      selectedPlan = plan;
      btn.disabled = false;
      // Selecciona th y todos los td de esa columna
      table.querySelectorAll('th[data-plan="'+plan+'"], td[data-plan="'+plan+'"]').forEach(cell => {
        cell.classList.add('selected-plan');
      });
    });
  });

  btn.addEventListener('click', function() {
    if(selectedPlan) {
      window.location.href = 'inscripcion.php?plan=' + selectedPlan;
    }
  });
});
</script>

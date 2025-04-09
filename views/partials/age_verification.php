<div class="age-verification-container text-center bg-light p-4 rounded">
    <h2>Verificación de Edad</h2>
    <p>Debes ser mayor de 18 años para acceder a este sitio.</p>
    <form action="/age-verification" method="POST">
        <div class="mb-3">
            <label for="birth_date" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Confirmar</button>
    </form>
</div>
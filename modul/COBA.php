<form action="proses_login.php" method="post" class="needs-validation" novalidate="">
				<div class="form-group form-floating-label">
					<div class="user-icon"><i class="fas fas fa-user"></i></div>
					<input type="text" id="username" name="username" class="form-control input-border-bottom" autocomplete="off" required="" fdprocessedid="txnfi7">
					<label for="username" class="placeholder">Username</label>
					<div class="invalid-feedback">Username tidak boleh kosong.</div>
				</div>

				<div class="form-group form-floating-label">
					<div class="user-icon"><i class="fas fa-lock"></i></div>
					<div class="show-password"><i class="flaticon-interface"></i></div>
					<input type="password" id="password" name="password" class="form-control input-border-bottom" autocomplete="off" required="" fdprocessedid="l8r0lt">
					<label for="password" class="placeholder">Password</label>
					<div class="invalid-feedback">Password tidak boleh kosong.</div>
				</div>

				<div class="form-action mt-2">
					<!-- button login -->
					<input type="submit" name="login" value="LOGIN" class="btn btn-secondary btn-rounded btn-login btn-block" fdprocessedid="6dc9u">
				</div>

				<!-- footer -->
				<div class="login-footer mt-5">
					<span class="msg">© 2021 -</span>
					<a href="https://pustakakoding.com/" class="text-brand">Pustaka Koding</a>.
				</div>
			</form>
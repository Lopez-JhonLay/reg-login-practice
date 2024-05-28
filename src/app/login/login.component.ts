import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { UserServiceService } from '../../service/user-service.service';
import { Router } from '@angular/router';
import { MatSnackBar } from '@angular/material/snack-bar';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  loginForm: FormGroup;
  isLogin = false;

  constructor(private userService: UserServiceService, private fb: FormBuilder, private router: Router, private _snackBar: MatSnackBar) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]]
    });
  }

  onSubmit() {
    if (this.loginForm.valid) {
      const user = this.loginForm.value;
      this.userService.loginUser(user).subscribe(response => {
        console.log(response);
        this.loginForm.reset();
        if (response.status === 'success') {
          this.isLogin = true;
          this._snackBar.open(response.message, 'Close', {
            duration: 5000,
          });
          this.router.navigate(['/dashboard']);
        }
      })
    }
  }
}

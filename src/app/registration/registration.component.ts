import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { UserServiceService } from '../../service/user-service.service';
import { MatSnackBar } from '@angular/material/snack-bar';

@Component({
  selector: 'app-registration',
  templateUrl: './registration.component.html',
  styleUrl: './registration.component.css'
})
export class RegistrationComponent {
  registrationForm: FormGroup;

  constructor(private userService: UserServiceService, private fb: FormBuilder, private _snackBar: MatSnackBar) {
    this.registrationForm = this.fb.group({
      username: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
      confirm_password: ['', [Validators.required, Validators.minLength(6)]]
    });
  }

  onSubmit() {
    if (this.registrationForm.valid) {
      const user = this.registrationForm.value;
      this.userService.addUser(user).subscribe(response => {
        //console.log(response);
        this._snackBar.open(response.message, 'Close', {
          duration: 5000,
        });
        this.registrationForm.reset();
      })
    }
  }
}

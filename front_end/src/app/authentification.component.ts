import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-authentification',
  templateUrl: './authentification.component.html',
  styleUrls: ['./authentification.component.css']
})
export class authentificationComponent {

  mail: string = '';
  password: string = '';

  constructor(private authService: AuthService, private router: Router) { }

  onSubmit(): void {
    this.authService.login(this.mail, this.password).subscribe(
      (response) => {
        if (response.role === 'admin') {
          this.router.navigate(['/gere-fleuriste']);
        } else if (response.role === 'user') {
          this.router.navigate(['/home']);
        } else {
          console.error('Unauthorized role');
        }
      },
      (error) => {
        console.error('Login failed:', error);
      }
    );
  }
  goToSignup(): void {
    this.router.navigate(['/signup']); // Redirige vers la page d'inscription
  }
}

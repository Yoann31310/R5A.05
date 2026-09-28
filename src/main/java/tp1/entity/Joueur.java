package tp1.entity;

import java.time.LocalDate;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;

@Entity // This tells Hibernate to make a table out of this class
public class Joueur {
  @Id
  @GeneratedValue(strategy=GenerationType.IDENTITY)
  private Integer id;

  private String nom;
  private String prenom;
  private int numeroLicence;
  private String statut;
  private LocalDate dateNaissance;
  private Double taille;
  private Double poids;

  public Integer getId() {
    return id;
  }

  public void setId(Integer id) {
    this.id = id;
  }

  public String getNom() {
    return nom;
  }

  public void setNom(String nom) {
    this.nom = nom;
  }

  public String getPrenom() {
    return prenom;
  }

  public void setPrenom(String prenom) {
    this.prenom = prenom;
  }

  public int getNumeroLicence() {
    return numeroLicence;
  }

  public void setNumeroLicence(int numeroLicence) {
    this.numeroLicence = numeroLicence;
  }

  public String getStatut() {
    return statut;
  }

  public void setStatut(String statut) {
    this.statut = statut;
  }

  public LocalDate getDateNaissance() {
    return dateNaissance;
  }

  public void setDateNaissance(LocalDate dateNaissance) {
    this.dateNaissance = dateNaissance;
  }

  public Double getTaille() {
    return taille;
  }

  public void setTaille(Double taille) {
    this.taille = taille;
  }

  public Double getPoids() {
    return poids;
  }

  public void setPoids(Double poids) {
    this.poids = poids;
  }

  @Override
  public String toString() {
    return "{" +
        " id='" + getId() + "'" +
        " nom='" + getNom() + "'" +
        " prenom='" + getPrenom() + "'" +
        " numeroLicence='" + getNumeroLicence() + "'" +
        " statut='" + getStatut() + "'" +
        " dateNaissance='" + getDateNaissance() + "'" +
        " taille='" + getTaille() + "'" +
        " poids='" + getPoids() + "'" +
        "}";
  }
}